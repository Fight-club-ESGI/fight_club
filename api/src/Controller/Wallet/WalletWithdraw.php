<?php

namespace App\Controller\Wallet;

use App\Entity\WalletTransaction;
use App\Enum\WalletTransaction\WalletTransactionStatusEnum;
use App\Enum\WalletTransaction\WalletTransactionTypeEnum;
use App\Repository\UserRepository;
use App\Service\Checkout\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class WalletWithdraw extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {}

    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        $user = $this->security->getUser();
        $wallet = $user->getWallet();

        if($user->getWallet()->getAmount() >= $amount){
            $wallet->setAmount($wallet->getAmount() - $amount);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            $transaction = new WalletTransaction();
            $transaction->setAmount($amount);
            $transaction->setWallet($wallet);
            $transaction->setType(WalletTransactionTypeEnum::WITHDRAWAL);
            $transaction->setStatus(WalletTransactionStatusEnum::ACCEPTED);
            $transaction->setTransaction("withdraw");
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            return new Response(json_encode(['message' => 'success', 'amount' => $wallet->getAmount()]), 200, ["Content-Type" => "application/json"]);
        } else {
            $transaction = new WalletTransaction();
            $transaction->setAmount($amount);
            $transaction->setWallet($wallet);
            $transaction->setType(WalletTransactionTypeEnum::WITHDRAWAL);
            $transaction->setStatus(WalletTransactionStatusEnum::REJECTED);
            $transaction->setTransaction("insufficient fund");
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            return new Response("insufficient fund", 400, ["Content-Type" => "application/json"]);
        }
    }
}
