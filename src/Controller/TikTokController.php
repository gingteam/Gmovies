<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TikTok\Driver\NativeDriver;
use TikTok\Driver\SnaptikDriver;
use TikTok\TikTokDownloader;

class TikTokController extends AbstractController
{
    #[Template]
    #[Route('/tiktok', name: 'app_tiktok')]
    public function index(): void
    {
    }

    #[Route('/api/tiktok', name: 'app_tiktok_api', methods: ['POST'])]
    public function api(Request $request): Response
    {
        [$url, $watermark] = array_map(fn ($value) => $request->request->get($value), ['url', 'watermark']);

        $driver = $watermark ? new SnaptikDriver() : new NativeDriver();
        $tiktok = new TikTokDownloader($driver);

        $success = $result = false;
        try {
            [$result, $success] = [$tiktok->getVideo((string) $url), true];
        } catch (\Throwable) {
        }

        return $this->json(compact('success', 'result'));
    }
}
