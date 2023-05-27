<?php

namespace App\Controller\Fighter;

use App\Entity\Fighter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GetFighter extends AbstractController
{
    public function __construct()
    {}

    public function __invoke(Request $request, Fighter $fighter): Fighter
    {
        return $fighter;
    }
}
