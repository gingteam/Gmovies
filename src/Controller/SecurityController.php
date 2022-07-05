<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/connect/google', name: 'connect_google_start')]
    public function connect(ClientRegistry $client): RedirectResponse
    {
        return $client->getClient('google')->redirect(['email', 'profile'], []);
    }

    #[Route(path: '/connect/google/check', name: 'connect_google_check')]
    public function connectCheck(Request $request, ClientRegistry $client): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl('admin'));
    }
}
