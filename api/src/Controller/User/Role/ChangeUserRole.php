<?php

namespace App\Controller\User\Role;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ChangeUserRole
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    )
    {
    }

    public function __invoke(Request $request, User $user): Response
    {
        $payload = json_decode($request->getContent(), true);

        if(isset($payload['roles'])) {
            $email = $user->getEmail();
            $role[] = $payload['roles'][0];

            if ($this->checkRole($role[0])) {
                $user->setRoles($role);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return New JsonResponse(['message' => "The role " . $role[0] . " has been applied to user " . $email ], 200);
            } else {
                return New JsonResponse(['message' => 'Your are not allowed to apply this role or this role does not exist'], 400);
            }
        } else {
            return New JsonResponse(['message' => "Role not found"], 400);
        }
    }

    private function checkRole(string $role): string
    {
        $admin_allowed_roles = ['ROLE_VVIP', 'ROLE_VIP', 'ROLE_USER', 'ROLE_ADMIN'];

        return (in_array($role, $admin_allowed_roles) && $this->security->isGranted('ROLE_ADMIN'));
    }
}
