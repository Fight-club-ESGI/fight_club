<?php
namespace App\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use App\Entity\Event;
use App\Entity\TicketCategory;
use App\Entity\WalletTransaction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\User;

final class RoleContextBuilder implements SerializerContextBuilderInterface
{
    public function __construct(
        private readonly SerializerContextBuilderInterface $decorated,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    )
    {
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass) {


            if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                switch($resourceClass) {
                    case Event::class:
                    case User::class:
                    case TicketCategory::class:
                        break;
                    default:
                        $context['groups'][] = $this->adminRequestMethodGroup($request->getMethod(), $normalization);
                }
            } else if ($this->authorizationChecker->isGranted('ROLE_SUPER_VIP')) {

            } else if ($this->authorizationChecker->isGranted('ROLE_VIP')) {

            } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {

            } else {

            }

            $context['groups'][] = $this->additionalRequestMethodGroup($request->getMethod(), $normalization);
            $context['enable_max_depth'] = true;
            if (str_starts_with($context['operation_name'], "self_")) {
                $context['groups'][] = $this->selfRequestMethodGroup($request->getMethod(), $normalization);
            }
        }

        return $context;
    }

    function adminRequestMethodGroup($method, $normalization) {
        if($normalization) {
            return 'admin:get';
        } else {

            return match ($method) {
                'POST' => 'admin:post',
                'PATCH' => 'admin:patch',
                default => '',
            };
        }
    }

    function additionalRequestMethodGroup($method, $normalization) {
        if($normalization) {
            return 'additional:get';
        } else {
            return match ($method) {
                'POST' => 'additional:post',
                'PATCH' => 'additional:patch',
                default => '',
            };
        }
    }

    function selfRequestMethodGroup($method, $normalization) {
        if($normalization) {
            return 'user:self:get';
        } else {
            return match ($method) {
                'POST' => 'user:self:post',
                'PATCH' => 'user:self:patch',
                default => '',
            };
        }
    }
}
