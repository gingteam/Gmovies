<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TikTokController extends AbstractController
{
    #[Route('/tiktok', name: 'app_tiktok')]
    public function index(): Response
    {
        return $this->render('tiktok/index.html.twig', [
            'controller_name' => 'TikTokController',
        ]);
    }
}
