<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Rank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Full Name',
                'constraints' => [new NotBlank()],
            ])
            ->add('contactNumber', TextType::class, [
                'label' => 'Contact Number',
                'constraints' => [new NotBlank()],
            ])
            ->add('bio', TextType::class, [
                'label' => 'Bio',
                'constraints' => [new NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [new NotBlank()],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'mapped' => false,
                'constraints' => [new NotBlank()],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'mapped' => false,
                'constraints' => [new NotBlank()],
            ])
            ->add('userRank', EntityType::class, [
                'class' => Rank::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Select a rank',
                'label' => 'User Rank',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
