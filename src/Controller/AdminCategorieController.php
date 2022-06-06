<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categorie")
 */
class AdminCategorieController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $categories = $paginator->paginate(
            // Doctrine Query, not results
            $categorieRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('admin_categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();

            $files = $form->get('image')->getData();

            if($files){

                $nameImage = Date('now')."-".uniqid().'.'.$files->guessExtension();

                $categorie->setImage($nameImage);

                $files->move($this->getParameter('image_categorie'), $nameImage);
            }
            $categorieRepository->add($categorie);
            $this->addFlash('success', 'La catégorie n° ' . $categorie->getId() . ' a été ajouter avec succès');
            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('admin_categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $ancienneImage = $categorie->getImage();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile= $form->get('image')->getData();

            if ($imageFile) {
                if($ancienneImage){
                    $pathImage = $this->getParameter('image_categorie')."/".$ancienneImage;
                    if(file_exists($pathImage)){
                        unlink($pathImage);
                    }

                }
                $nameImage = Date('now') . "-" . uniqid() . "." . $imageFile->guessExtension();

                $categorie->setImage($nameImage);

                $imageFile->move($this->getParameter('image_categorie'), $nameImage);

            } else {
                $categorie->setImage($ancienneImage);
            }
            $categorieRepository->add($categorie);
            $this->addFlash('success', 'La catégorie n° ' . $categorie->getId() . ' a été modifier avec succès');

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $idCategorie = $categorie->getId();
        $image_categorie = $categorie->getImage();
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {

            if($image_categorie){
                $pathImage= $this->getParameter('image_categorie').'/'. $image_categorie;
                if(file_exists($pathImage)){
                    unlink($pathImage);
                }
                
            }

            $this->addFlash('success', 'La catégorie n° ' . $idCategorie . ' a été supprimer avec succès');
            $categorieRepository->remove($categorie);
        }

        return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
