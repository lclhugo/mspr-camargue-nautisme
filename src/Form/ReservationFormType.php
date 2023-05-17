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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
        $reservation = $builder->getData();
        $ReservationRepository = $this->reservationRepository;
        $builder
            ->add("emailClient", EmailType::class, [
                'label' => 'Email'
            ])
            ->add("nameClient", TextType::class, [
                'label' => 'Nom'
            ])
//            ->add("dateLocation")
            //use the $equipment that is an array of equipment that are not reserved for the date and location as options
            ->add("equipment", EntityType::class, [
                'label' => 'Equipement',
                'class' => Equipment::class,
                'choices' => $ReservationRepository->findAvailableEquipmentsByDateAndLocation($reservation->getDateLocation(), $reservation->getLocation()),
                'choice_label' => 'description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select one equipment',
                    ]),
                ],
            ]);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
