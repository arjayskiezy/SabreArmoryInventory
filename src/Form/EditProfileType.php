<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClasses = 'mt-1 block w-full rounded-lg border border-gray-300 shadow-sm 
                         focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                         sm:text-sm px-3 py-2';

        $labelClasses = 'block text-sm font-medium text-gray-700';
        $wrapperClasses = 'mb-5';

        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Full Name',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Enter full name',
                ],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => $inputClasses,
                    'placeholder' => 'Enter email address',
                ],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm New Password',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
