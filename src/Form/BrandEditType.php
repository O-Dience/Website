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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BrandEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['constraints'=>[ new NotBlank([
                'message'=> 'Veuillez saisir un nom d\'utilisateur'
            ])],'label'=>'Nom de votre entreprise'])
            ->add(
                'birthdate', 
                BirthdayType::class, ["widget"=>"single_text", "label"=>"Date de création"],)
            ->add('categories', EntityType::class, [
                'multiple'=>true,
                'class' => Category::class,
                'choice_label' => 'label'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Présentation de votre entreprise/marque:',
                'required' => false,])
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
            ->add('email', EmailType::class, [
                'constraints'=> [new NotBlank([
                'message' => 'Veuillez entrer une adresse e-mail valide',
            ])],'label'=>'Votre e-mail'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                'first_options'  => ['constraints'=> [
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe nécessite au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],'label' => 'Mot de passe'],
                'second_options' => ['label' => 'Retapez votre mot de passe'],
                'mapped' => false,
                'required' => false,
            ])
            ;
    
    }   

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

