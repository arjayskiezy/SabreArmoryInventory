<?php

namespace App\Form;

use App\Entity\Stock;
use App\Entity\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClasses = 'mt-1 block w-full rounded-lg border border-gray-300 shadow-sm 
                         focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                         sm:text-sm px-3 py-2';
        $labelClasses = 'block text-sm font-medium text-gray-700';
        $wrapperClasses = 'mb-5';

        $builder
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a unit',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => ['class' => $inputClasses, 'placeholder' => 'Enter quantity'],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
