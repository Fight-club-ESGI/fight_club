<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\TicketEvent;
use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use App\Enum\Order\OrderStatusEnum;
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CartCheckoutWallet extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckoutService $checkoutService,
        private readonly Security $security,
    ) {
    }

    public function __invoke(Cart $cart): Response
    {
        if (!$cart instanceof Cart) {
            throw $this->createNotFoundException('Cart not found');
        }

        $totalPrice = $cart->getCartItems()->reduce(fn (int $carry, CartItem $item) => $carry + ($item->getTicketEvent()->getPrice() * $item->getQuantity()), 0);
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
        $this->checkoutService->paymentWallet($walletTransaction);
        $this->entityManager->flush();

        [$createdTickets, $refundTickets] = $this->checkoutService->generateTickets($order, $cart->getCartItems());


        if (count($refundTickets) > 0) {
            $refundPrice = array_reduce($refundTickets, fn (int $carry, $item) => $carry + $item['ticketEvent']->getPrice() * $item['quantity'], 0);

            $refundWalletTransaction = (new WalletTransaction)
                ->setWallet($wallet);

            $this->checkoutService->refund($refundWalletTransaction, $refundPrice);
        }

        $order->setStatus(OrderStatusEnum::SUCCESS);
        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Checkout success',
            'data' => [
                'order' => $order,
                'tickets' => $createdTickets,
            ],
        ]);
    }
}
