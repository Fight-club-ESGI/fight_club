<?php
namespace App\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use App\Repository\UserRepository;
use PhpParser\Error;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\User;

final class UserContextBuilder implements SerializerContextBuilderInterface
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

        if ($resourceClass === User::class) {
            if (isset($context['groups'])) {
                if ($normalization) {
                    # Normalization part
                    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                        $context['groups'][] = 'admin:user:get';
                        $context['groups'][] = 'admin:user:post';
                        $context['groups'][] = 'admin:user:patch';
                    } else if ($this->authorizationChecker->isGranted('ROLE_SUPER_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                        if ($request->attributes->get('id')) {
                            $user = $this->userRepository->find($request->attributes->get('id'));

                            if($this->security->getUser() === $user) {
                                $context['groups'][] = 'user:self';
                            }
                        }
                    } else {

                    }
                } else {
                    # Denormalization part
                    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                        $context['groups'][] = 'admin:user:post';
                    } else if ($this->authorizationChecker->isGranted('ROLE_SUPER_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_VIP')) {

                    } else {

                    }
                }
            }
        }

        return $context;
    }
}
