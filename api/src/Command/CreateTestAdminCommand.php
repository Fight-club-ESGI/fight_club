<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'test:generate-users',
    description: 'Creates a new admin for test.',
    aliases: ['test:add-users-test'],
    hidden: false
)]
class CreateTestAdminCommand extends Command
{
    // the command description shown when running "php bin/console list"
    protected static $defaultDescription = 'Creates a new admin for test.';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create an admin...')
        ;
    }

    // ...
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Creation of users for test',
            '============',
            '',
        ]);

        $this->createUsersForTest();

        $output->write('Creation of users for test');

        return Command::SUCCESS;
    }

    private function createUsersForTest(): void
    {
        for ($i = 0; $i < 2; $i++) {
            if (!$this->userRepository->findBy(["email" => $i . "_test_admin@test.com"])) {
                $user = new User();
                $user->setEmail($i . "_test_admin@test.com");
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    "password"
                );
                $user->setPassword($hashedPassword);
                $user->setRoles(["ROLE_ADMIN"]);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }

        for ($i = 0; $i < 3; $i++) {
            if (!$this->userRepository->findBy(["email" => $i . "_test_user@test.com"])) {
                $user = new User();
                $user->setEmail($i . "_test_user@test.com");
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    "password"
                );
                $user->setPassword($hashedPassword);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $wallet = new Wallet();
                $wallet->setUsers($user);
                $wallet->setAmount(20000);
                $this->entityManager->persist($wallet);
                $this->entityManager->flush();
            }
        }
    }
}