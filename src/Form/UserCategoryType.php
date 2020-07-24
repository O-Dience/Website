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
            ->add('category',  EntityType::class, 
                [
                    'placeholder'=>"Séléctionnez une catégorie",
                    "label" => "Centre d'intérêt",
                    "class" => Category::class,
                    "choice_label" => "label"
                ])
                // add hidden field for javascript
            ->add('hiddenValue', HiddenType::class,
                [
                    'mapped' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'lastOptionValue'
                    ]
                ]
            )
            ->add('notification',  CheckboxType::class, 
                [
                "label" => "Souhaitez-vous recevoir les alertes?",
                "required"=>false
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