<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\PhotoAnnonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/annonce')]
final class AnnonceController extends AbstractController
{
    #[Route(name: 'app_annonce_index', methods: ['GET'])]
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $query = $request->query->get("query") ?? "";
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findByNameAndUser($this->isGranted("ROLE_ADMIN") ? null : $this->getUser(), $query),
            'query' => $query
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imageDirectory
    ): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $prix = $form->get("prix")->getData();
            if ($prix <= 0) {
                $this->addFlash("danger", "Prix ne dois pas être 0 ou moins");
                return $this->redirectToRoute("app_annonce_new", [], Response::HTTP_SEE_OTHER);
            }

            $images = $form->get('images')->getData();
            if ($images) {
                $this->formSaveImage($images, $annonce, $entityManager, $slugger, $imageDirectory);
            }

            $annonce->setUser($this->getUser());

            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imageDirectory): Response
    {
        if ($annonce->getUser() != $this->getUser()) {
            # tentative de modification par usurpation
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            if ($images) {
                $this->formSaveImage($images, $annonce, $entityManager, $slugger, $imageDirectory);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    private function formSaveImage($images, Annonce $annonce, EntityManagerInterface $entityManager, SluggerInterface $slugger, $imageDirectory)
    {
        foreach ($images as $image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $image->move($imageDirectory, $newFilename);
            } catch (FileException $e) {
                $this->addFlash("danger", "Failed to upload images");
                return $this->redirectToRoute("app_annonce_new", [], Response::HTTP_SEE_OTHER);
            }

            $photoEntity = new PhotoAnnonce();
            $photoEntity->setAnnonce($annonce);
            $photoEntity->setPath("/uploads/images/$newFilename");
            $entityManager->persist($photoEntity);
        }
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($annonce->getUser() != $this->getUser()) {
            # tentative de suppression par usurpation
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        }

        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
