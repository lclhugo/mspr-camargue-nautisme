<?php

namespace App\Form;

use App\Entity\Equipment;
use App\Entity\RentalLocation;
use App\Entity\Reservation;
use App\Repository\EquipmentRepository;
use App\Repository\ReservationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationFormType extends AbstractType
{
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $ReservationRepository = $this->reservationRepository;
        $builder
            ->add("emailClient", null)
            ->add("nameClient", null)
            ->add("dateLocation", null)
            ->add("equipment", null)
            ->add("Location", null)
            ->add("dateReservation", null);
//          ->add('createdAt', null)

        }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
