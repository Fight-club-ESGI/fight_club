<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\PendingTicket;
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
class CartCheckoutStripe extends AbstractController
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

        $order = (new Order())
            ->setUser($user)
            ->setPrice($totalPrice);

        foreach ($cart->getCartItems() as $cartItem) {
            $ticketEvent = $cartItem->getTicketEvent();
            $quantity = $cartItem->getQuantity();

            $pendingTicket = (new PendingTicket)
                ->setTicketEvent($ticketEvent)
                ->setQuantity($quantity)
                ->setOrder($order);

            $order->addPendingTicket($pendingTicket);
            $this->entityManager->persist($pendingTicket);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $checkout = $this->checkoutService->checkout($user, $totalPrice, WalletTransactionTypeEnum::STRIPE_PAYMENT, [], 1, true, $order);

        return new Response($checkout->url, 200, ["Content-Type" => "application/json"]);
    }
}
