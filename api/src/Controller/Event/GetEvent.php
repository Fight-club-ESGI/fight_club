<?php

namespace App\Controller\Event;

use App\Entity\Event;
use App\Entity\Fighter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GetEvent extends AbstractController
{
    public function __construct()
    {}

    public function __invoke(Request $request, Event $event): Event
    {
        return $event;
    }
}
