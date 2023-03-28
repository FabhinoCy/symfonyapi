<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users/me", name="api_users_me", methods={"GET"})
     */
    public function me(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        return $this->json([
            'user' => $user,
        ]);
    }
}