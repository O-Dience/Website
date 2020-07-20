<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InfluencerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'constraints' =>
                    [
                        new NotBlank([
                            'message' => 'Veuillez saisir un nom d\'utilisateur'
                        ]),
                        new Length([
                            'min' => 4,
                            'max' => 12,
                            'minMessage' => 'Votre nom d\'utilisateur doit comporter au moins {{ limit }} caratères',
                            'maxMessage' => 'Votre nom d\utilisateur ne peut pas comporter plus de {{ limit }} caratères',
                            'allowEmptyString' => false,
                        ])
                    ],
                    'label' => 'Nom d\'utilisateur'
                ]
            )
            ->add(
                'birthdate',
                BirthdayType::class,
                [
                    'widget' => 'single_text',
                    'label' => 'Date de naissance',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'E-mail'
                ]
            )

            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                    'first_options'  =>
                    [
                        'label' => 'Mot de passe'
                    ],
                    'second_options' =>
                    [
                        'label' => 'Confirmation'
                    ],
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit contenir au moins 8 caractères',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de passe'
                ]
            )
            ->add('pictureFile', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ])
                ],
            ])
            ->add('categories', EntityType::class, [
                'multiple' => true,
                'class' => Category::class,
                'choice_label' => 'label'
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
