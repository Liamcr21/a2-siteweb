<?php

namespace App\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepo, CategoryRepository $categoryRepo,): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articleRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
        ]);
    }
}
