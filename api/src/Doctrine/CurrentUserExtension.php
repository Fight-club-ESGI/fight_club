<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Bet;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if (str_starts_with($operation->getName(), "self_")) { # regex pour check si le nom de l'operation commence par self_
            $this->addWhere($queryBuilder, $resourceClass);
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        if (str_starts_with($operation->getName(), "self_")) { # regex pour check si le nom de l'operation commence par self_
            $this->addWhere($queryBuilder, $resourceClass);
        }
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        #if (Wallet::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN') || null === $user = $this->security->getUser()) {
        #    return;
        #}
        $user = $this->security->getUser();

        switch ($resourceClass) {
            case User::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.id = :current_user', $rootAlias));
                $queryBuilder->setParameter('current_user', $user->getId());
                break;
            case Wallet::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.users = :current_user', $rootAlias));
                $queryBuilder->setParameter('current_user', $user->getId());
                break;
            case WalletTransaction::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.wallet = :wallet', $rootAlias));
                $queryBuilder->setParameter('wallet', $user->getWallet()->getId());
                $queryBuilder->orderBy(sprintf('%s.createdAt', $rootAlias), 'DESC');
                break;
            case Cart::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s._user = :user', $rootAlias));
                $queryBuilder->setParameter('user', $user->getId());
                break;
            case Bet::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.bettor = :bettor', $rootAlias));
                $queryBuilder->setParameter('bettor', $user->getId());
            default:
        }
    }
}
