<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', null, [
                'label' => 'Référence du produit',
            ])
            ->add('libelle', null, [
                'label' => 'Nom du produit',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('prix', null, [
                'label' => 'Prix 0.01 - 9999.99 (€)',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo (JPG ou PNG)',
                'mapped' => false, // Important !
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => "Merci d'uploader une image JPG ou PNG",
                    ])
                ],
            ])
            ->add('stock', null, [
                'label' => 'Stock disponible',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'csrf_protection' => true,

        ]);
    }
}
