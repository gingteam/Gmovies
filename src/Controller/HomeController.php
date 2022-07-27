<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @return array{pagination: PaginationInterface}
     */
    #[Template]
    #[Route('/', name: 'app_home')]
    public function index(
        MovieRepository $movieRepository,
        PaginatorInterface $paginator,
        Request $request
    ): array {
        $query = $movieRepository->createQueryBuilder('m')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return ['pagination' => $pagination];
    }
}
