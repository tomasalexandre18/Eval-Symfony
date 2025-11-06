<?php

namespace App\Controller;

use App\Entity\CategorieAnnonce;
use App\Form\CategorieAnnonceType;
use App\Repository\CategorieAnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorie')]
final class CategorieAnnonceController extends AbstractController
{
    #[Route(name: 'app_categorie_annonce_index', methods: ['GET'])]
    public function index(CategorieAnnonceRepository $categorieAnnonceRepository): Response
    {
        return $this->render('categorie_annonce/index.html.twig', [
            'categorie_annonces' => $categorieAnnonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieAnnonce = new CategorieAnnonce();
        $form = $this->createForm(CategorieAnnonceType::class, $categorieAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieAnnonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_annonce/new.html.twig', [
            'categorie_annonce' => $categorieAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_annonce_show', methods: ['GET'])]
    public function show(CategorieAnnonce $categorieAnnonce): Response
    {
        return $this->render('categorie_annonce/show.html.twig', [
            'categorie_annonce' => $categorieAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieAnnonce $categorieAnnonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieAnnonceType::class, $categorieAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_annonce/edit.html.twig', [
            'categorie_annonce' => $categorieAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieAnnonce $categorieAnnonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieAnnonce->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorieAnnonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
