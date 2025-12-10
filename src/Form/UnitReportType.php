<?php

namespace App\Form;

use App\Entity\UnitInstance;
use App\Enum\ItemStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userUnits = $options['user_units'];

        $builder
            ->add('unit', EntityType::class, [
                'class' => UnitInstance::class,
                'choices' => $userUnits,
                'choice_label' => fn(UnitInstance $unit) => $unit->getSerialNumber() . ' - ' . $unit->getUnitType()?->getName(),
                'label' => 'Select Unit',
                'placeholder' => 'Choose a unit',
                'mapped' => false, // We’ll set the unit manually
                'required' => true,
            ])
            ->add('itemStatus', ChoiceType::class, [
                'choices' => array_combine(array_map(fn($s) => $s->value, ItemStatus::cases()), array_map(fn($s) => $s->value, ItemStatus::cases())),
                'label' => 'Issue Type',
                'required' => true,
            ])
            ->add('reportNotes', TextareaType::class, [
                'label' => 'Description / Notes',
                'attr' => ['rows' => 4],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user_units' => [],
        ]);
    }
}
