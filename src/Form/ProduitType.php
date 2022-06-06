<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                "required" => false
            ])
            ->add('description', TextareaType::class,[
                "required" => false,
                "attr" => [
                    "cols" => 8
                ]
            ])
            ->add('prix', NumberType::class, [
                "required" => false
            ])
            ->add('image' , FileType::class, [
                "required" => false,
                "data_class" => null
            ])
            ->add('categorie', EntityType::class,[
                "class" => Categorie::class,
                "choice_label" => "titre"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
