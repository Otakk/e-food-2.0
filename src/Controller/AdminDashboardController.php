<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="app_admin_dashboard")
     */
    public function index(ProduitRepository $produitRepository, MenuRepository $menuRepository, CategorieRepository $categorieRepository,  Request $request): Response
    {
        $search = $request->get('search');

        if($search)
        {
            $produits = $produitRepository->findFilter($search);
        }else{
            $produits = [];
        }

        // $produitRepository->findFilter()
        return $this->render('admin_dashboard/index.html.twig', [
            'produits' => $produits,
            'staticProduit' => count($produitRepository->findAll()),
            'staticCategorie' => count($categorieRepository->findAll()),
            'staticMenu' => count($menuRepository->findAll())
        ]);
    }


}
