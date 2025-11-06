<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) {
            // pour les admin la page index ne sert à rien, ils ont tout dans /annonces donc les rediriger
            return $this->redirectToRoute("app_annonce_index");
        }

        $query = $request->query->get("query") ?? "";
        return $this->render('index/index.html.twig',[
            "annonces" => $annonceRepository->findByName($query),
            'query' => $query
        ]);
    }
}
