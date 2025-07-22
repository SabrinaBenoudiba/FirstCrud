<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use App\Repository\CrudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    // public function homePage(EntityManagerInterface $entityManager): Response
     public function homePage(CrudRepository $crudRepo): Response
    {
        $datas = $crudRepo->findall();
        // $datas = $entityManager
            // ->getRepository(Crud::class)
            // ->findAll();
        return $this->render('home_page/homePage.html.twig', [
            'controller_name' => 'HomePageController',
            'datas' => $datas,
        ]);
    }

    #[Route('/create', name: 'app_create_form')]  #coller la fonction d'avant et la modifier 
    public function create_form(Request $request, EntityManagerInterface $entityManager): Response 
    { # il faut cliquer droit si des erreurs dans les class et importer la class
        $crud = new Crud(); # On initialise une variable qui va être égale à une nouvelle instance de la table Crud (initialisation d'un objet vide à remplir avec les données du formulaire).
        $form = $this->createForm(CrudType::class, $crud); # On initialise un formulaire basé sur la classe CrudType, en liant ce formulaire à l'objet $crud. On utilise $this car createForm est une méthode qui appartient à la classe du contrôleur (hérité de AbstractController).
        $form->handleRequest($request); # On "traite" la requête HTTP reçue (GET ou POST) : s'il s'agit d'un POST, on remplit l'objet $crud avec les données soumises par l'utilisateur.
        if ( $form->isSubmitted() && $form->isValid()){ #si mon formulaire et soumis et valide
        
            $entityManager->persist($crud); # dans ce cas là tu persist sur crud (il prend les données puis attend et c'est flush qui emmène les données)
            $entityManager->flush(); # flush il emmène les données dans la bdd du persist

            $this->addFlash('notice', 'Soumission réussi !!');

            return $this->redirectToRoute('app_home_page'); # si tout est validé, retour sur la Home Page

        }
        return $this->render('form/createForm.html.twig', [
            'form' => $form->createView() # variable (pour créer un formulaire) form qui a pour valeur formulaire et on lui demande créer une vue sur ce formulaire
        ]);
    }

    #[Route('/update', name: 'update')]
    public function update(Request $request, EntityManagerInterface $entityManager, $id): Response 
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud); 
        $form->handleRequest($request); 
        if ( $form->isSubmitted() && $form->isValid()){ 
        
            $entityManager->persist($crud); 
            $entityManager->flush(); 

            $this->addFlash('notice', 'Modification réussi !!');

            return $this->redirectToRoute('app_home_page'); 
        }
         return $this->render('form/updateForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete', name: 'delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response 
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud); 
        $form->handleRequest($request); 
        if ( $form->isSubmitted() && $form->isValid()){ 
        
            $entityManager->persist($crud); 
            $entityManager->flush(); 

            $this->addFlash('notice', 'Modification réussi !!');

            return $this->redirectToRoute('app_home_page'); 
        }
         return $this->render('form/deleteForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
