<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Service\Panier;
use App\Entity\Commande;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="app_paiement")
     */
    public function index(Panier $panier, CategorieRepository $categorieRepository): Response
    {

        // $session->remove('panier');
        $total = $panier->getTotal();

        // Ajouter la clé secret du stripe (Mettre votre propre clef secret de test)
        Stripe::setApiKey('sk_test_51L7f3JKWD0dldeto6R0xzbZiDNrj9PIEO0mT6MTOhucaw5jW1mZjdzeB87IWEPOnpfeXilXoi2Fs41v2p5dI4JVm00rz5WZ9KU');
        
        // 9.46min +90xp victoire


        /* https://stripe.com/docs/payments/payment-intents
        
            Pour plus d 'information
            "amount" : Montant a payer conveti en centime ( 12.50 * 100)
            "currency" : le devise utilisé  
        */
        $intent = PaymentIntent::create([
            'amount' => round($total * 100), 
            'currency' => 'eur',
        ]);

        $clientSecret = $intent->client_secret;

        return $this->render('paiement/paiement.html.twig', [
            'clientSecret' => $clientSecret,
            'items' => $panier->getFullCart(),
            'total' => $panier->getTotal(),
            "categories" => $categorieRepository->findAll()
        ]);
    }

    /**
     * 
     * @Route("/merci", name="success_url")]
     * 
     */
    public function successUrl(Panier $panier, EntityManagerInterface $em, SessionInterface $session, CategorieRepository $categorieRepository)
    {

        // Recuperer la commande passé + le paiement est passé
        $commandePasser = $panier->getFullCart();

        // créer un nouveau objet Commande
        $commande = new Commande;

        
        // Modifier les valeur de l'entity Commande 
        $commande->setProduits(serialize($commandePasser));
        $commande->setMontant($panier->getTotal());
        $commande->setDateCreation(new DateTime("now"));
        $commande->setUser($this->getUser());

        // persister les données 
        $em->persist($commande);
        // Enregistrer les données dans la bdd
        $em->flush();

        // Supprimer le panier de la session
        $session->remove('panier');
        
        // renvoyer la page Merci si paiment est bien passé
        return $this->render('paiement/merci.html.twig', [
            'items' => $commandePasser,
            'total' => $panier->getTotal(),
            "categories" => $categorieRepository->findAll()
        ]);
    }
    


    /**
     * @Route("/cancel-url", name="cancel_url")]
     *  
     */
    public function cancelUrl(Panier $panier, CategorieRepository $categorieRepository): Response
    {
        // renvoyer la page Merci si paiment a été refuser 
        return $this->render('payment/cancel.html.twig', [
            'items' => $panier->getFullCart(),
            'total' => $panier->getTotal(),
            "categories" => $categorieRepository->findAll()
        ]);
    }
}
