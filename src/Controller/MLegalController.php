<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MLegalController extends AbstractController
{
    #[Route('/m/legal', name: 'app_m-legal')]
    public function index(): Response
    {
        return $this->render('m_legal/index.html.twig', [
            'controller_name' => 'MLegalController',
        ]);
    }
}
