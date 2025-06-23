<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Form\EvenementsForm;
use App\Repository\CategoryRepository;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/evenements')]
final class EvenementsController extends AbstractController
{
    // Créer une route nommée app_evenements_index, retourne cette vue dans evenements/index.html.twig
    #[Route(name: 'app_evenements_index', methods: ['GET'])]
    public function index(EvenementsRepository $evenementsRepository): Response
    {
        return $this->render('evenements/index.html.twig', [
            // fournit les évènements dans la vue 
            'evenements' => $evenementsRepository->findAll(),
        ]);
    }

    // créer une route nommée app_evenements_new
    #[Route('/new', name: 'app_evenements_new', methods: ['GET', 'POST'])]
    //créer une fonction "new" avec la classe request et l'api qui créer l'instance
    // fonction qui instancie un evenement et si le formulaire est valide alors sauvegarde l'evenement
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    { // instancie un objet de la classe evenement
        $evenement = new Evenements(); // cree la variable $evenement, qui crée une nouvelle classe evenements
        // on déclare un objet qui contient un formulaire fournit avec symfony
        $form = $this->createForm(EvenementsForm::class, $evenement);// crée la variable form qui appelle l'objet présent et créer un formulaire de classe "evenementsform" avec comme donnée $evenement
        $form->handleRequest($request);// permet de gerer la maniere dont est validé le formulaire

        if ($form->isSubmitted() && $form->isValid()) { // si le formulaire est soumis et valide, crée l'objet
            // enregistre en BDD
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER); // redirige $this sur la route app_evenements_index
        }

        return $this->render('evenements/new.html.twig', [ // une fois le formulaire soumis, retourne ça à la vue avec l'evenement et le formulaire
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_show', methods: ['GET'])]
    public function show(Evenements $evenement): Response
    {
        return $this->render('evenements/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementsForm::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenements/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenements_delete', methods: ['POST'])]
    public function delete(Request $request, Evenements $evenement, EntityManagerInterface $entityManager): Response
    { // système de protection pour l'action de supprimer l'evenement 
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenements_index', [], Response::HTTP_SEE_OTHER);
    }



    // #[Route('/category/action', name: 'app_evenements_action', methods: ['GET'])]
    // public function actionCategory(EvenementsRepository $evenementsRepository): response
    // {
    //     // $evenements = $evenementsRepository->findByCategoryName('action');
    //     return $this->render('evenements/action.html.twig');
    //     // ['evenements' => $evenements]);
    // }

    #[Route('/category/{slug}', name: 'app_evenements_by_category', methods: ['GET'], defaults: ['slug' => null])]
    public function byCategory(?string $slug, EvenementsRepository $evenementsRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $evenements = [];
        if ($slug) {
            $evenements = $evenementsRepository->findByCategorySlug($slug);
        }

        return $this->render('evenements/find_by_category.html.twig', [
            'evenements' => $evenements,
            'categories' => $categories,
            'current_slug' => $slug,
        ]);
    }
}
