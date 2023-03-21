<?php

namespace App\Controller;

use App\Entity\WalletTransaction;
use App\Enum\WalletTransactionStatusType;
use App\Enum\WalletTransactionTypeType;
use App\Repository\UserRepository;
use App\Service\CheckoutService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class WalletWithdraw extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request, Security $security, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'];

        $user = $userRepository->find($security->getUser()->getId());
        $wallet = $user->getWallet();

        if($user->getWallet()->getAmount() > $amount){
            $wallet->setAmount($wallet->getAmount() - $amount);
            $this->entityManager->persist($wallet);
            $this->entityManager->flush();

            $transaction = new WalletTransaction();
            $transaction->setAmount($amount);
            $transaction->setWallet($wallet);
            $transaction->setType(WalletTransactionTypeType::WITHDRAWAL);
            $transaction->setStatus(WalletTransactionStatusType::ACCEPTED);
            $transaction->setTransaction("withdraw");
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            return new Response(json_encode(['message' => 'success', 'amount' => $wallet->getAmount()]), 200, ["Content-Type" => "application/json"]);
        } else {
            $transaction = new WalletTransaction();
            $transaction->setAmount($amount);
            $transaction->setWallet($wallet);
            $transaction->setType(WalletTransactionTypeType::WITHDRAWAL);
            $transaction->setStatus(WalletTransactionStatusType::REJECTED);
            $transaction->setTransaction("insufficient fund");
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            return new Response('failed', 200, ["Content-Type" => "application/json"]);
        }
    }
}
