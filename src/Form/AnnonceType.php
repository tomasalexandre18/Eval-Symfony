<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\CategorieAnnonce;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Annonce $annonce*/
        $annonce = $options["data"];

        $builder
            ->add('titre')
            ->add('description')
            ->add('prix')
            ->add('localisation')
            ->add('categorie', EntityType::class, [
                'class' => CategorieAnnonce::class,
                'choice_label' => 'libelle',
            ])
            ->add('images', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File(
                                maxSize: '5M',
                                extensions: ['jpg', 'png', 'webp', 'jpeg'],
                                extensionsMessage: 'Please upload a valid image'
                            )
                        ]
                    ])
                ],
                'data' => $annonce
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
