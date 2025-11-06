<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'annonces' => $annonceRepository->findAll()
        ]);
    }
}
