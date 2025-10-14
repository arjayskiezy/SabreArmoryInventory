<?php

namespace App\Form;

use App\Entity\Unit;
use App\Entity\UnitType as UnitTypeEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClasses = 'mt-1 block w-full rounded-lg border border-gray-300 shadow-sm 
                         focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 
                         sm:text-sm px-3 py-2';
        $labelClasses = 'block text-sm font-medium text-gray-700';
        $wrapperClasses = 'mb-5';

        $builder
            ->add('name', null, [
                'attr' => ['class' => $inputClasses, 'placeholder' => 'Enter unit name'],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => $inputClasses . ' resize-none', 'rows' => 3],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => $inputClasses, 'placeholder' => '0.00'],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('image_path', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (jpg, png, gif, webp).',
                    ])
                ],
                'row_attr' => ['class' => $wrapperClasses],
            ])
            ->add('type', EntityType::class, [
                'class' => UnitTypeEntity::class,
                'choice_label' => 'name',
                'placeholder' => 'Select unit type',
                'attr' => ['class' => $inputClasses],
                'label_attr' => ['class' => $labelClasses],
                'row_attr' => ['class' => $wrapperClasses],
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('u')->orderBy('u.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
        ]);
    }
}
