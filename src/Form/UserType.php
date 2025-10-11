<?php

namespace App\Form;

use App\Entity\Rank;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Tailwind-style class presets
        $inputClasses = 'mt-1 block w-full rounded-lg border border-gray-300 shadow-sm 
                         focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                         sm:text-sm px-3 py-2';
        $labelClasses = 'block text-sm font-medium text-gray-700';
        $wrapperClasses = 'mb-5';

        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => $inputClasses, 'placeholder' => 'Enter email address'],
                'label' => 'Email Address',
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles',
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
                'attr' => ['class' => 'space-x-3 text-sm text-gray-700'],
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => $inputClasses, 'placeholder' => 'Enter password'],
                'label' => 'Password',
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('full_name', TextType::class, [
                'attr' => ['class' => $inputClasses, 'placeholder' => 'Enter full name'],
                'label' => 'Full Name',
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('user_rank', EntityType::class, [
                'class' => Rank::class,
                'choice_label' => 'name', 
                'placeholder' => 'Select user rank',
                'label' => 'User Rank',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('r')->orderBy('r.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
