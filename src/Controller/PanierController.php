<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Service\Panier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PanierController extends AbstractController
{
    /**
     * 
     * @Route("/panier", name="panier_index")
     */
    public function index(Panier $panier, CategorieRepository $categorieRepository): Response
    {

        /* Envoi tous le panier vers la page panier
            getFullCart() : obtenir tous le contenu du panier
            getTotal() : Obtenir le total du panier 

        */
        return $this->render('panier/panier.html.twig', [
            'items' => $panier->getFullCart(),
            'total' => $panier->getTotal(),
            "categories" => $categorieRepository->findAll()
        ]);
    }


    /**
     * 
     * @Route("panier/ajouter/{id}", name="panier_ajouter")
     */

    public function ajouter($id, Panier $panier, Request $request)
    {
        //Ajouter un produit dans le panier 
        $panier->ajouter($id);
        // Obtenir la route(URL) de la page precedent 
        $route = $route = $request->headers->get('referer');
        
        // Une redirection vers la route 
        return  $this->redirect($route);
    }


    /**
     * 
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */

    public function supprimer($id, Panier $panier, Request $request)
    {

        // Supprimer un produit du panier 
        $panier->supprimer($id);

        // Obtenir la route(URL) de la page precedent 
        $route = $route = $request->headers->get('referer');
        
        // Une redirection vers la route 
        return  $this->redirect($route);

    }

    /**
     * 
     * @Route("/panier/update/{id}/{qty}", name="panier_update")
     */

    public function update($id, $qty, Panier $panier, Request $request)
    {

        // Modifier la quantité d'un produit du panier 
        $panier->update($id, $qty);
        $route = $route = $request->headers->get('referer');
        
        return  $this->redirect($route);

    }
}
