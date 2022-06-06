<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu")
 */
class AdminMenuController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_menu_index", methods={"GET"})
     */
    public function index(MenuRepository $menuRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $Menus = $paginator->paginate(
            // Doctrine Query, not results
            $menuRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('admin_menu/index.html.twig', [
            'menus' => $Menus 
        ]);
    }

    /**
     * @Route("/new", name="app_admin_menu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MenuRepository $menuRepository): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menu = $form->getData();

            $files = $form->get('image')->getData();

            if($files){

                $nameImage = Date('now')."-".uniqid().'.'.$files->guessExtension();

                $menu->setImage($nameImage);

                $files->move($this->getParameter('image_menus'), $nameImage);
            }

            $menuRepository->add($menu);
            $this->addFlash('success', 'Le menu n° ' . $menu->getId() . ' a été ajouter avec succès');

            return $this->redirectToRoute('app_admin_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->render('admin_menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_menu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Menu $menu, MenuRepository $menuRepository): Response
    {
        $ancienneImage = $menu->getImage();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile= $form->get('image')->getData();

            if ($imageFile) {
                if($ancienneImage){
                    $pathImage = $this->getParameter('image_menus')."/".$ancienneImage;
                    if(file_exists($pathImage)){
                        unlink($pathImage);
                    }

                }
                $nameImage = Date('now') . "-" . uniqid() . "." . $imageFile->guessExtension();

                $menu->setImage($nameImage);

                $imageFile->move($this->getParameter('image_menus'), $nameImage);

            } else {
                $menu->setImage($ancienneImage);
            }
            $menuRepository->add($menu);
            $this->addFlash('success', 'Le menu n° ' . $menu->getId() . ' a été modifier avec succès');

            return $this->redirectToRoute('app_admin_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_menu_delete", methods={"POST"})
     */
    public function delete(Request $request, Menu $menu, MenuRepository $menuRepository): Response
    {

        $idMenu = $menu->getId();
        $image_menu = $menu->getImage();
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {

            if($image_menu){
                $pathImage= $this->getParameter('image_menus').'/'. $image_menu;
                if(file_exists($pathImage)){
                    unlink($pathImage);
                }
            }
            $menuRepository->remove($menu);
        }

        $this->addFlash('success', 'Le menu n° ' . $idMenu . ' a été supprimer avec succès');
        return $this->redirectToRoute('app_admin_menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
