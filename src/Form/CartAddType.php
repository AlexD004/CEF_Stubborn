<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CartAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('size', ChoiceType::class, [
                'label' => 'Taille',
                'choices' => [
                    'XS' => 'XS',
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez choisir une taille.']),
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'data' => 1,
                'attr' => ['min' => 1],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir une quantité.']),
                    new Assert\GreaterThanOrEqual([
                        'value' => 1,
                        'message' => 'La quantité doit être au moins de 1.',
                    ]),
                ],
            ])
            ->add('productId', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}