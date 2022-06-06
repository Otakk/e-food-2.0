<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/produit")
 */
class AdminProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {

        //Ajouter la pagination et afficher 10 enregistrement par page 

        $produits = $paginator->paginate(
            // Doctrine Query, not results
            $produitRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        // Envoyé les données Vers la page index.html.twig
        return $this->render('admin_produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        // Créer nouvelle instant de la class Produit 
        $produit = new Produit();

        // Creation du furmulaire avec le contenu de la class ProduitType dans Le dossier FORM
        $form = $this->createForm(ProduitType::class, $produit);

        // Récuperer les données saisie dans le formulaire 
        $form->handleRequest($request);


        // Tester si le button submit est clicker et que le formaulaire est valide ( Aucun erreur dans le formulaire )
        if ($form->isSubmitted() && $form->isValid()) {

            
            // déclarer un variable $produit avec les valeur du formulaire 
            $produit = $form->getData();

            // recuperer la valeur du champ IMAGE 
            $files = $form->get('image')->getData();


            // Si l utilisateur a selectionné une image 
            if($files){


                /* Générer un nouveau nom de fichier image 

                Date('now') : Date et heur du jour 
                uniqid() : Fonction qui génere un Id unique 
                guessExtension() : Obtenir l'extension de notre fichier .jpg || .png || au autre
                
                */

                $nameImage = Date('now')."-".uniqid().'.'.$files->guessExtension();

                //Modifier la valeur de notre Image Produit

                $produit->setImage($nameImage);

                /* Deplacer le fichier Image vers le dossier public/Image
                    getParameter('image_upload'): Obtenir le chemin dans le quel  on enregistre l'image 

                    image_upload :Variable declarer dans le dossier Config/Service.yaml----> Parametrs
                */

                $files->move($this->getParameter('image_upload'), $nameImage);
            }

            // Enregistrer notre produit dans la base de donées 
            $produitRepository->add($produit);

            // Envoyé un message flash dans la session 
            $this->addFlash('success', 'Le produit n° ' . $produit->getId() . ' a été ajouter avec succès');

            // Redirection vers la page INdex
            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        /* Dans le cas OU l'utilisateur n'a pas valider le formulaire 
            Ou cliquer sur le bouton nouveau 
            On renvoi l 'instant de notre class Poduit 
            et aussi une formulaire VIDE $FORM    
        */
        return $this->renderForm('admin_produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {

        /*
            Afficher les detail d' un produit qui a l ID passé dans l url
        
        */
        return $this->render('admin_produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        /* 
            Modifiaction d'un produit qui a l ID /{ID}
        */


        // Recuperer la valeur de l'iamge du produit passé en parametre {ID}
        $ancienneImage = $produit->getImage();

        // Création du formaulaire 
        $form = $this->createForm(ProduitType::class, $produit);

        //Recuperer les données du formulaire 
        $form->handleRequest($request);

        // Tester si le button submit est clicker et que le formaulaire est valide ( Aucun erreur dans le formulaire )
        if ($form->isSubmitted() && $form->isValid()) {

            // Recuper la valeur de l image selectionné 
            $imageFile= $form->get('image')->getData();

            // Si on a selectionner une iamge 
            if ($imageFile) {

                // Si on a une image dans la BDD
                if($ancienneImage){
                    // On recuperer son chemin 
                    $pathImage = $this->getParameter('image_upload')."/".$ancienneImage;
                    // tester si le fichier image Existe
                    // file_exists("chemeni du fichier)
                    if(file_exists($pathImage)){

                        // Supprimer l image du repertoire 
                        unlink($pathImage);
                    }

                }

                // Attribuer un nouveau nom l image selectionner ( nom Unique pour eviter les doublon )
                $nameImage = Date('now') . "-" . uniqid() . "." . $imageFile->guessExtension();

                // Modifier la valeur de l image Produit
                $produit->setImage($nameImage);

                // Deplacer l'image vers le dossier Public/Chemin declarer dans Service.yaml ( parametrs)
                
                $imageFile->move($this->getParameter('image_upload'), $nameImage);

            } else {
                /*
                    Si l'utilisateur n'a pas selectioné une image on attribu a notre image produit
                    la valeur deja enregistré 
                */ 
                $produit->setImage($ancienneImage);
            }

            // Ajouter le produit dans la BDD

            $produitRepository->add($produit);

            // Renvoi d'un message flash
            $this->addFlash('success', 'Le produit n° ' . $produit->getId() . ' a été modifier avec succès');

            // Redirection vers la page Index
            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }


        // Afficher la page edit avec les données du $produit qui a l ID passé en parametre
        return $this->renderForm('admin_produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {

        // Recuperer l ID du produit a supprimer 
        $idProduit = $produit->getId();

        // recuperer l image du produit
        $image_produit = $produit->getImage();


        // avoir un jeton valide ( pour une securité de notre données )
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {

            // Si image existe
            if($image_produit){
                // Recuperer le chemin de l image
                $pathImage= $this->getParameter('image_upload').'/'. $image_produit;

                // Test si le fichier existe
                if(file_exists($pathImage)){
                     // Supprimer le fichier Image
                    unlink($pathImage);
                }
            }

            // Supprimer le produit de la base de donnée
            $produitRepository->remove($produit);
        }

        // Envoyé un message flash
        $this->addFlash('success', 'Le produit n° ' . $idProduit . ' a été supprimer avec succès');

        // E,voyé une redirection vers la page Index
        return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
