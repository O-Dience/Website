<?php

namespace App\Form;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\SocialNetwork;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('pictureFile', FileType::class, [
                'label' => 'Image d\'illustration',
                'mapped' => false,
                'required' => false,
                'constraints' => [new Image()]
            ])
            ->add('socialNetworks', EntityType::class, [
                'multiple'=>true,
                'class' => SocialNetwork::class,
                'choice_label' => 'name'
            ])
            ->add('categories', EntityType::class, [
                'multiple'=>true,
                'class' => Category::class,
                'choice_label' => 'label'
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
