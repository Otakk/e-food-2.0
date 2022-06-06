<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Produit;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                "required" => false
            ])
            ->add('description', TextareaType::class,[
                "required" => false,
                "attr" => [
                    "cols" => 8
                ]
            ])
            ->add('prix', NumberType::class,[
                "required" => false,

            ])
            ->add('produit', EntityType::class,[
                "class" => Produit::class,
                "choice_label" => "titre",
                "multiple" => true,
                "expanded" => true
            ])
            ->add('image', FileType::class, [
                "required" => false,
                "data_class" => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
