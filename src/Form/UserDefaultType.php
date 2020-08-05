<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\UserCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserDefaultType extends AbstractType
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
                            'maxMessage' => 'Votre nom d\'utilisateur ne peut pas comporter plus de {{ limit }} caratères',
                            'allowEmptyString' => false,
                        ])
                    ],
                    'label' => 'Nom d\'utilisateur',
                    'help' => 'Entre 4 et 12 caractères'
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
                    'label' => 'Mot de passe',
                    'help' => 'Au moins 8 caractères'
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
                'help' => 'Maximum 1024k'
            ])
            ->add('categories', EntityType::class, [
                'multiple' => true,
                'mapped' => false,
                'class' => Category::class,
                'choice_label' => 'label',
                'help' => 'Vous pouvez choisir jusqu\'à 3 catégories'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ([
                    'rows' => 5,
                    'cols' => 40,
                ]),
                'constraints' => [
                    new Length([
                        // max length allowed by Symfony for security reasons
                        'max' => 300,
                        'min' => 50,
                        'maxMessage' => 'Votre description est trop longue, elle ne doit pas dépasser {{ limit }} caractères',
                        'minMessage' => 'Votre description est trop courte, elle doit au moins contenir {{ limit }} caractères',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
