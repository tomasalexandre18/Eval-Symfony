<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\PhotoAnnonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/annonce/image")]
final class ImageController extends AbstractController
{
    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, PhotoAnnonce $photo, EntityManagerInterface $entityManager, #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imageDirectory): Response
    {
        if ($photo->getAnnonce()->getUser() != $this->getUser()) {
            # tentative de suppression par usurpation
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        }

        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->getPayload()->getString('_token'))) {
            AnnonceController::deleteImage($photo, $imageDirectory);
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        if ($request->getPayload()->getString("redirect")) {
            return $this->redirect($request->getPayload()->getString("redirect"));
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
