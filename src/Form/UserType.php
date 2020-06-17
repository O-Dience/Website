<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur'
            ])
            /*->add('email', EmailType::class, [
                'label' => 'Adresse email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Date de naissance/création'
            ])
            ->add('pictureFile', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Influenceur' => 'ROLE_INFLUENCER',
                    'Marque' => 'ROLE_BRAND'
                ]
            ])   
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'multiple' => true
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
