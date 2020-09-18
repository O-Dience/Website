<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\UserCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'category',
                EntityType::class,
                [
                    'multiple' => false,
                    'placeholder' => "Séléctionnez une catégorie",
                    "label" => "Centre d'intérêt",
                    "class" => Category::class,
                    "choice_label" => "label"
                ]
            )
            ->add(
                'notification',
                CheckboxType::class,
                [
                    "label" => "Recevoir les alertes",
                    "required" => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCategory::class,
        ]);
    }
}
