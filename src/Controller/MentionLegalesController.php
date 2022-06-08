<?php

namespace App\Controller;

use App\Service\Panier;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MentionLegalesController extends AbstractController
{
    /**
     * @Route("/mention/legales", name="app_mention_legales")
     */
    public function index(Panier $panier, CategorieRepository $categorieRepository): Response
    {
        return $this->render('mention_legales/mention.html.twig', [
            'controller_name' => 'MentionLegalesController',
            "items" => $panier->getFullCart(),
            "categories" => $categorieRepository->findAll(),
        ]);
    }
}
