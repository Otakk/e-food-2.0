<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Service\Panier;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile_index")
     */
    public function index(CommandeRepository $commandeRepository, Panier $panier, CategorieRepository $categorieRepository): Response
    {

        // Obtenir l'utilsateur en cours( Utilisateur connecté)
        $user =$this->getUser();

        // Obtenir tous les commandes passé par cette utilisateur 
        $commandes = $commandeRepository->findCommande($user);

        // declarer un tableau vide   dans laquelle on enregistre tous les commande
        $allCommandes=[];


        /* 
        "numero"=> $value->getId() Numero de la commande
        "dateFature" => $value->getDateCreation() : Date de creation de la commande
        "montant" => $value->getMontant() "Montant de la commande
        "produits" =>  unserialize($value->getproduits()) : Liste des produits de la commande 
        */

        foreach ($commandes as $key => $value) {

            $allCommandes[$key] = [
                "numero"=> $value->getId(),
                "dateFature" => $value->getDateCreation(),
                "montant" => $value->getMontant(),
                "produits" =>  unserialize($value->getproduits())

            ];
           
        }
        // renvoi des données vers la page Profile
        return $this->render('profile/profile.html.twig', [
            'commandes' => $allCommandes,
            "items" => $panier->getFullCart(),
            "total" => $panier->getTotal(),
            "categories" => $categorieRepository->findAll()
        ]);
    }
}
