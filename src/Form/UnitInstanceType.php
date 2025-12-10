<?php

namespace App\Form;

use App\Entity\Unit;
use App\Entity\UnitInstance;
use App\Entity\User;
use App\Enum\UnitStatus;
use App\Enum\ItemStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitInstanceType extends AbstractType
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
            ->add('serialNumber', TextType::class, [
                'attr' => ['class' => $inputClasses, 'placeholder' => 'Enter Serial Number'],
                'label' => 'Serial Number',
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('purchasedDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => $inputClasses],
                'label' => 'Purchased Date',
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => UnitStatus::ACTIVE,
                    'Damaged' => UnitStatus::DAMAGED,
                    'Retired' => UnitStatus::RETIRED,
                ],
                'choice_label' => fn(UnitStatus $status) => $status->name,
                'choice_value' => fn(?UnitStatus $status) => $status?->value,
                'label' => 'Unit Status',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('itemStatus', ChoiceType::class, [
                'choices' => [
                    'Good' => ItemStatus::GOOD,
                    'Maintenance' => ItemStatus::MAINTENANCE,
                    'Low Ammo' => ItemStatus::LOW_AMMO,
                    'Urgent' => ItemStatus::URGENT,
                ],
                'choice_label' => fn(ItemStatus $status) => $status->name,
                'choice_value' => fn(?ItemStatus $status) => $status?->value,
                'label' => 'Item Status',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('weaponType', EntityType::class, [
                'class' => Unit::class,
                'choice_label' => 'name', // or 'id', but 'name' is more user-friendly
                'placeholder' => 'Select Weapon Type',
                'label' => 'Weapon Type',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'full_name',
                'placeholder' => 'Select Owner',
                'label' => 'Owner',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UnitInstance::class,
        ]);
    }
}
