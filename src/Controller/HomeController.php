<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Service\Panier;
use App\Entity\Categorie;
use App\Repository\MenuRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * 127.0.0.0:8000
     * @Route("/", name="app_home")
     */
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository,  MenuRepository $menuRepository, Panier $panier): Response
    {

        /*
            La page Home
            $produits : les produits de la bdd
            $menus : les menus de la bdd
        */

        // Récuperer les données de la bdd
        $produits = $produitRepository->findAll();

        // Récuperer les menus de la bdd
        $menus = $menuRepository->findAll();

        /* 
            Envoyer la page Home avec les parametres suivant
            "produits" => tous les produits de la bdd
            "menus" => tous les menus de la bdd
            "categories" => tous les categorises de la bdd
            "items" => tous les produits du panier
            "total" => Le total du panier
        */ 

        return $this->render('home/home.html.twig', [
            "produits" => $produits,
            "menus" => $menus,
            "categories" => $categorieRepository->findAll(),
            "items" => $panier->getFullCart(),
            "total" => $panier->getTotal()

        ]);
    }

    /**
     * 127.0.0.0:8000
     * @Route("/produit", name="app_produit")
     */
    public function produit(Request $request, ProduitRepository $produitRepository, MenuRepository $menuRepository, CategorieRepository $categorieRepository, Panier $panier): Response
    {

        // Recuperer le mot saisie dans le champ de recherche 

        $search = $request->get('search');


        // Si l'utilisateur a saisie un mot
        if ($search) {

            /* On fait une recherche dans la base de donnée avec le mot saisie dans recherche
                Requete SQL "SELECT * FROM PRODUIT WHERE produit.titre LIKE % $search %
                findFilter($search) ; fonction créer dans ProduitRepository Class
            */
            $produitsResult = $produitRepository->findFilter($search);
            // Si le nombre d'enregistrement trouvé et supperieur a 0
            if (count($produitsResult) > 0) {
                // On affect le resultat obtenu au variable $produits
                $produits = $produitsResult;
            } else {
                // Sinon recuper tous les produit
                $produits = $produitRepository->findAll();
            }


        } else {
            $produits = $produitRepository->findAll();
        }

        // Obtenir tous les categories
        $categories = $categorieRepository->findAll();

        // Obtenir tous les menus
        $menus = $menuRepository->findAll();



        /* 
            Envoyer la page produit avec les parametres suivant
            "produits" => tous les produits de la bdd
            "menus" => tous les menus de la bdd
            "categories" => tous les categorises de la bdd
            "items" => tous les produits du panier
            "total" => Le total du panier
        */ 
        return $this->render('home/produit.html.twig', [
            "produits" => $produits,
            "menus" => $menus,
            "categories" => $categories,
            "items" => $panier->getFullCart(),
            "total" => $panier->getTotal()
        ]);
    }

    /**
     * 127.0.0.0:8000
     * @Route("/produit/afficher/{id}", name="app_afficher")
     */
    public function show(CategorieRepository $categorieRepository, Produit $produit, Panier $panier): Response
    {
        /*
            Afficher les details du produit passé dans l URL 
        */
        return $this->render('home/fiche_produit.html.twig', [
            "produit" => $produit,
            "items" => $panier->getFullCart(),
            "total" => $panier->getTotal(),
            "categories" => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/categorie/{id}",name="app_categorie")
     */
    public function categorie(Categorie $categorie, CategorieRepository $categorieRepository, Panier $panier)
    {
        // Recuperer tous les produit d'une categorie avec son ID passé en parametre

        $produits = $categorie->getProduits();

        return $this->render('home/categorie.html.twig', [
            "produits" => $produits,
            "categorie" => $categorie,
            "categories" => $categorieRepository->findAll(),
            "items" => $panier->getFullCart(),
            "total" => $panier->getTotal()
        ]);
    }
}
