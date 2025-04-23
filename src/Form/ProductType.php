<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', MoneyType::class, [
                'currency' => 'EUR'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => !$options['is_edit'],
                'constraints' => $options['is_edit'] ? [] : [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Choisissez une image JPG ou PNG valide.',
                    ])
                ],
            ])
            ->add('isFeatured', CheckboxType::class, [
                'label' => 'Mettre ce produit à la une',
                'required' => false,
                'mapped' => true,
                'attr' => ['class' => 'custom-checkbox']
            ])
            ->add('stockXS', IntegerType::class, [
                'label' => 'Stock XS',
                'required' => false,
                'mapped' => true,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-stock'
                ]
            ])
            ->add('stockS', IntegerType::class, [
                'label' => 'Stock S',
                'required' => false,
                'mapped' => true,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-stock'
                ]
            ])
            ->add('stockM', IntegerType::class, [
                'label' => 'Stock M',
                'required' => false,
                'mapped' => true,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-stock'
                ]
            ])
            ->add('stockL', IntegerType::class, [
                'label' => 'Stock L',
                'required' => false,
                'mapped' => true,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-stock'
                ]
            ])
            ->add('stockXL', IntegerType::class, [
                'label' => 'Stock XL',
                'required' => false,
                'mapped' => true,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-stock'
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'is_edit' => false,
        ]);
    }
}
