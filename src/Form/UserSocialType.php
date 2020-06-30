<?php

namespace App\Form;

use App\Entity\SocialNetwork;
use App\Entity\UserSocial;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('social', EntityType::class, [
                "label"=>"Réseau à ajouter :",
                'expanded'=>true,
                'multiple'=>false, 
                'class' => SocialNetwork::class,
                'choice_label' => 'name'
            ])
            ->add('link', UrlType::class, [
                'constraints'=> [ new NotBlank([
                    'message' => 'Veuillez ajouter le lien de votre profil',
                ]),
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSocial::class,
        ]);
    }
}
