<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Panier;
use App\Form\RegistrationFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Panier $panier, CategorieRepository $categorieRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'items' => $panier->getFullCart(),
            'total' => $panier->getTotal(),
            "categories" => $categorieRepository->findAll()

        ]);
    }


    /**
     * 
     * @Route("/updateUser", name="app_updateUser")
     */

     public function updateUser(Request $request, EntityManagerInterface $em)
     {
        $user = $this->getUser();

         $user->setAdresse($request->get('adresse'));
         $user->setCodePostal($request->get('codePostal'));
         $user->setNumTelephone($request->get('numTelephone'));

         $em->persist($user);
         $em->flush();

         $this->addFlash('success', 'L\'utilisateur  ' . $user->getNom() . ' a été modifier avec succès');

         return $this->redirectToRoute('profile_index');


     }

    
}
