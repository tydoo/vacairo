<?php

namespace App\Form;

use App\Entity\Vacation;
use App\Entity\VacationType as VacationTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class VacationType extends AbstractType {
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('type', EntityType::class, [
                'class' => VacationTypeEntity::class,
                'choice_label' => 'name',
            ])
            ->add('hours', ChoiceType::class, [
                'label' => 'Nombre d\'heures',
                'choices' => [
                    '7 heures' => 7,
                    '8 heures' => 8,
                    '9 heures' => 9,
                    '10 heures' => 10,
                    '11 heures' => 11,
                    '12 heures' => 12,
                    '13 heures' => 13,
                    '14 heures' => 14,
                    '15 heures' => 15,
                    '16 heures' => 16,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer une vacation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Vacation::class,
        ]);
    }
}
