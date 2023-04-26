<?php
namespace App\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use App\Entity\WalletTransaction;
use App\Repository\UserRepository;
use PhpParser\Error;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\User;

final class RoleContextBuilder implements SerializerContextBuilderInterface
{
    public function __construct(
        private readonly SerializerContextBuilderInterface $decorated,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly Security $security,
        private readonly UserRepository $userRepository
    )
    {
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass) {
            switch($resourceClass) {
                case WalletTransaction::class:
                case User::class:
                    break;
            }

            if (isset($context['groups'])) {

                if ($normalization) {
                    # Normalization part
                    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                        $context['groups'][] = 'admin:get';
                    } else if ($this->authorizationChecker->isGranted('ROLE_SUPER_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {

                    } else {

                    }
                    $context['groups'][] = 'additional:get';
                    $context['enable_max_depth'] = true;
                } else {
                    # Denormalization part
                    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                        $context['groups'][] = 'admin:post';
                        $context['groups'][] = 'admin:patch';
                    } else if ($this->authorizationChecker->isGranted('ROLE_SUPER_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {

                    } else {

                    }
                    $context['groups'][] = 'additional:post';
                    $context['enable_max_depth'] = true;
                }
            } else {
                if ($normalization) {
                    $context['groups'][] = 'additional:get';
                    $context['enable_max_depth'] = true;
                } else {
                    $context['groups'][] = 'additional:post';
                    $context['enable_max_depth'] = true;
                }
            }
        }

        return $context;
    }
}
