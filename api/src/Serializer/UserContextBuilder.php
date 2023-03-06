<?php
namespace App\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use PhpParser\Error;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\User;

final class UserContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass === User::class) {
            if (isset($context['groups'])) {
                if ($normalization) {
                    # Normalization part
                    if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
                        $context['groups'][] = 'super_admin:user:get';
                    } else if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                        $context['groups'][] = 'admin:user:post';
                        $context['groups'][] = 'admin:user:patch';
                    } else if ($this->authorizationChecker->isGranted('ROLE_SUPER_VIP')) {

                    } else if ($this->authorizationChecker->isGranted('ROLE_VIP')) {

                    } else {

                    }
                } else {
                    # Denormalization part
                    if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
                        $context['groups'][] = 'super_admin:user:post';
                    } else if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {

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
