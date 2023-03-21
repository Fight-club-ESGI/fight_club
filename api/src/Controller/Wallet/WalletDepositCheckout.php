<?php

namespace App\Controller;

use App\Enum\WalletTransactionTypeType;
use App\Repository\UserRepository;
use App\Service\CheckoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class WalletDepositCheckout extends AbstractController
{
    private CheckoutService $checkout;

    public function __construct(CheckoutService $checkoutService) {
        $this->checkout = $checkoutService;
    }

    public function __invoke(Request $request, Security $security, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        $checkout_session = $this->checkout->checkout(
            $userRepository->find($security->getUser()->getId()),
            $amount,
            WalletTransactionTypeType::DEPOSIT,
        );

        return new Response(
             json_encode(["url" => $checkout_session->url, "amount" => $security->getUser()->getWallet()->getAmount()]), 200, ["Content-Type" => "application/json"]
        );
    }
}
