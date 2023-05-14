<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\TicketEvent;
use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\CartRepository;
use App\Service\Checkout\CheckoutService;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CartCheckout extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckoutService $checkoutService,
        private readonly Security $security,
    ) {
    }

    public function __invoke(Request $request, Cart $cart): Cart
    {
        $params = json_decode($request->getContent());
        $type = $params->type;

        in_array($type, ['wallet', 'stripe']) || throw $this->createNotFoundException('Type not found');

        if (!$cart instanceof Cart) {
            throw $this->createNotFoundException('Cart not found');
        }

        $totalPrice = $cart->getCartItems()->reduce(fn (int $carry, CartItem $item) => $carry + ($item->getTicketEvent()->getPrice() * $item->getQuantity()), 0);

        switch ($type) {
            case 'wallet':
                $this->payWithWallet($cart, $totalPrice);
                break;
            case 'stripe':
                $this->payWithStripe($cart, $totalPrice);
                break;
        }

        return $cart;

        dd($totalItems, $totalPrice, $cart->getUser()->getWallet()->getAmount());

        switch ($type) {
            case 'wallet':
                $this->payWithWallet($cart, $totalPrice);
                break;
            case 'stripe':
                $this->payWithStripe($cart, $totalPrice);
                break;
        }

        return $cart;
    }

    private function payWithWallet(Cart $cart, int $totalPrice): void
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $wallet = $user->getWallet();

        if (!$wallet) {
            throw $this->createNotFoundException('Wallet not found');
        }

        if ($wallet->getAmount() < $totalPrice) {
            throw $this->createNotFoundException('Not enough money');
        }

        $order = (new Order())
            ->setUser($user)
            ->setPrice($totalPrice);

        $this->entityManager->persist($order);

        $walletTransaction = $this->checkoutService->recordWalletTransaction($wallet, $totalPrice, WalletTransactionStatusEnum::PENDING, WalletTransactionTypeEnum::WALLET_PAYMENT);
        $this->checkoutService->payment($walletTransaction);

        [$createdTickets, $refundTickets] = $this->generateTickets($order, $cart->getCartItems());

        $this->entityManager->flush();

        if (count($refundTickets) > 0) {
            $refundPrice = array_reduce($refundTickets, fn (int $carry, $item) => $carry + $item['ticketEvent']->getPrice() * $item['quantity'], 0);

            $refundWalletTransaction = (new WalletTransaction)
                ->setWallet($wallet);

            $this->checkoutService->refund($refundWalletTransaction, $refundPrice);
        }
    }

    private function generateTickets($order, $cartItems)
    {
        $tickets = [];
        $refundTickets = [];

        $this->entityManager->getConnection()->beginTransaction();

        try {

            foreach ($cartItems as $cartItem) {
                $remainingQuantity = $cartItem->getQuantity();
                $ticketEventId = $cartItem->getTicketEvent()->getId();

                while ($remainingQuantity > 0) {
                    $ticketEvent = $this->entityManager->find(TicketEvent::class, $ticketEventId, LockMode::PESSIMISTIC_WRITE);

                    if ($ticketEvent->getMaxQuantity() > $ticketEvent->getTickets()->count()) {
                        $ticket = (new Ticket())
                            ->setTicketEvent($cartItem->getTicketEvent())
                            ->setOrder($order);

                        $this->entityManager->persist($ticket);
                        $this->entityManager->flush();
                        $tickets[] = $ticket;
                        $remainingQuantity--;
                    } else {
                        break;
                    }
                }

                if ($remainingQuantity > 0) {
                    $refundTickets[] = [
                        'ticketEvent' => $cartItem->getTicketEvent(),
                        'quantity' => $remainingQuantity,
                    ];
                }

                $this->entityManager->remove($cartItem);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return [$tickets, $refundTickets];
        } catch (Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
