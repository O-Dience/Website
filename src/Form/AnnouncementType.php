<?php

namespace App\Form;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\SocialNetwork;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, 
            ['constraints'=>[ new NotBlank([
                'message'=> 'Veuillez donner un titre à cette annonce'
            ])],
            'attr'=> (['placeholder'=>'Ex: Recherche une égérie pour O\'Dience']),
            'label'=>'Titre de l\'annonce'],)
            ->add('content', TextareaType::class,  [
                'constraints'=>[ new NotBlank([
                'message'=> 'Précisez ce que vous recherchez']),
                new Length([
                    'min' => 200,
                    'minMessage' => 'La description de l\'annonce nécessite au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
                ],
                'attr'=>([ 
                    'rows'=> 10 , 
                    'cols'=> 40,
                    'placeholder'=>'Décrivez le profil idéal pour votre projet, en quoi il consistera, la durée, le nombre de personne dont vous avez besoin et ce que vous pouvez leur apporter en retour ! ']),
                'label'=>'Description du projet'
                ])
            ->add('pictureFile', FileType::class, [
                'label' => 'Image d\'illustration',
                'mapped' => false,
                'required' => false,
                'constraints' => [new Image()]
            ])
            ->add('socialNetworks', EntityType::class, [
                'multiple'=>true,
                'class' => SocialNetwork::class,
                'choice_label' => 'name',
                'label'=> 'Plateforme requise (facultatif)',
                'required'=>false,
            ])
            ->add('categories', EntityType::class, [
                'multiple'=>true,
                'class' => Category::class,
                'choice_label' => 'label',
                'label'=>'Catégorie de l\'annonce'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
