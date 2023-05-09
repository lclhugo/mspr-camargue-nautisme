<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameClient')
            ->add('emailClient')
            ->add('equipment', EntityType::class, [
                "class" => Equipment::class,
                "choice_label" => "category.name"
            ])
            ->add('dateLocation', DateType::class, [
//                'format' => 'dd MM yyyy',
                'widget' => 'single_text'
            ])
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
