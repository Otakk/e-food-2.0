<?php


namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    protected $session;
    protected $produitRepository;


    public function __construct(SessionInterface $session, ProduitRepository $produitRepository)
    {
        // $session->clear();
        $this->session = $session;
        $this->produitRepository = $produitRepository;
    }


    /**
     * 
     * Ajouter un produit au panier
     * 
     */
    public function ajouter(int $id)
    {

        // J'appelle le panier s'il existe 
        $panier = $this->session->get('panier', []);

        // Tester si le panier n'est pas vide
        if (!empty($panier[$id])) {
            // Augmenter la quantité du panier 
            $panier[$id]++;
        } else {
            //Sinon on met la quantité a 1
            $panier[$id] = 1;
        }

        // Modifier le panier
        $this->session->set("panier", $panier);
    }


    //Supprimer un produit du panier 

    public function supprimer(int $id)
    {
        $panier = $this->session->get("panier", []);

        if (!empty($panier)) {
            // Supprimer le produit qui a comme clé (KEY) la valeur di $ID 
            unset($panier[$id]);
        }

        // Modifier le panier 
        $this->session->set('panier', $panier);
    }


    // Obtenir tous le contenu du panier
    public function getFullCart(): array
    {
        $panier = $this->session->get("panier", []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                // Attribué a chaque Id(Key) le produit qui a son ID 
                'product' => $this->produitRepository->find($id),
                'quantity' => $quantity
            ];
        }
        // Renvoyer un tableau avec tous les produits du panier 
        return $panierWithData;
    }

    public function getTotal()
    {
        $total = 0;
        $panierWithData = $this->getFullCart();

        foreach ($panierWithData as $item) {
            $total += $item['product']->getPrix() * $item['quantity'];
        }

        return $total;
    }

    public function update($id, $qty)
    {
        $panier = $this->session->get('panier', []);
        
        // Tester si le panier n'est pas vide
        if (!empty($panier[$id])) {
            // Augmenter la quantité du panier 
            $panier[$id]= $qty;
        } 
        // Modifier le panier
        $this->session->set("panier", $panier);
    }
}
