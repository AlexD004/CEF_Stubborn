<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom utilisateur'
            ])
            ->add('deliveryAddress', TextType::class, [
                'label' => 'Adresse de livraison'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => ['autocomplete' => 'new-password']
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['autocomplete' => 'new-password']
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Merci de renseigner un mot de passe',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        // max length pour la sécurité
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
