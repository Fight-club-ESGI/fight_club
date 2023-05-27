<?php

namespace App\Controller\Wallet;

use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\UserRepository;
use App\Service\Checkout\CheckoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class WalletDepositCheckout extends AbstractController
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly Security $security,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'] * 100;

        if ($amount < 500) {
            return new Response(
                "Amount must be superior or equal at 5€",
                400,
                ["Content-Type" => "application/json"]
            );
        }
        $checkout_session = $this->checkout->checkout(
            $this->userRepository->find($this->security->getUser()->getId()),
            $amount,
            WalletTransactionTypeEnum::DEPOSIT,
        );

        return new Response(
            json_encode(["url" => $checkout_session->url]),
            200,
            ["Content-Type" => "application/json"]
        );
    }
}
