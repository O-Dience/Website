<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SocialNetwork;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InfluencerEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['constraints'=>[ new NotBlank([
                'message'=> 'Veuillez saisir un nom d\'utilisateur'
            ])],'label'=>'Nom d\'utilisateur'])
            ->add(
                'birthdate', 
                BirthdayType::class, ["widget"=>"single_text", "label"=>"Date de naissance"],)
            ->add('email', EmailType::class, ['label'=>'Votre e-mail'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Retapez votre mot de passe'],
                'mapped' => false,
                'required' => false,
            ])
            ->add(
                'birthdate', 
                BirthdayType::class, ["widget"=>"single_text", "label"=>"Date de naissance"],)
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
                'multiple'=>true,
                'class' => Category::class,
                'choice_label' => 'label'
            ])
            // ->add('userSocials', CollectionType::class, [
            //     'entry_type' => UserSocialType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            //     "by_reference" => false
            // ])
        ;
    }  

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
