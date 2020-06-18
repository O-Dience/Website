<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, ['constraints'=>[ new NotBlank([
                'message'=> 'Veuillez saisir le nom de la catégorie'
            ])],
                'label'=>'Label'])

            ->add('picto', FileType::class, [
                "label" => "Picto",
                "mapped" => false,
                "required" => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k'
                    ]),
                    new NotBlank([
                        'message'=> 'Veuillez enregistrer une image'
                    ])
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
