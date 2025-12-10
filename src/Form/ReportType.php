<?php
// src/Form/ReportType.php
namespace App\Form;

use App\Entity\Report;
use App\Entity\UnitInstance;
use App\Enum\ItemStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userUnits = $options['user_units'] ?? [];

        $inputClasses = 'w-full rounded-lg border-gray-300 shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out';
        $labelClasses = 'block text-sm font-medium text-gray-700 mb-1';

        $builder
            ->add('unit', EntityType::class, [
                'class' => UnitInstance::class,
                'choices' => $userUnits,
                'choice_label' => fn(UnitInstance $u) => $u->getSerialNumber() . ' - ' . ($u->getUnitType()?->getName() ?? 'Unspecified'),
                'label' => 'Unit to Report',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
            ])
            ->add('itemStatus', ChoiceType::class, [
                'choices' => [
                    'Good' => ItemStatus::GOOD,
                    'Maintenance' => ItemStatus::MAINTENANCE,
                    'Low Ammo' => ItemStatus::LOW_AMMO,
                    'Urgent' => ItemStatus::URGENT,
                ],
                'label' => 'Current Unit Status',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Detailed Issue Description',
                'attr' => ['class' => $inputClasses . ' h-32 resize-none', 'placeholder' => 'Describe the issue clearly (e.g., "Barrel not aligned")'],
                'label_attr' => ['class' => $labelClasses],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'user_units' => [],
        ]);
    }
}
