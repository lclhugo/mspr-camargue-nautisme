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
            ->add('nameClient', TextType::class, [
                'label' => 'Nom *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('emailClient', TextType::class, [
                'label' => 'Email *',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('location', EntityType::class, [
                "class" => RentalLocation::class,
                "choice_label" => "address"
            ])
            //have a default value = today
            ->add('dateLocation', DateType::class, [
                'data' => new \DateTime(),
                'widget' => 'single_text',
            ])
            ->add('equipment', EntityType::class, [
                "class" => Equipment::class,
                "choice_label" => "category.name",
                "query_builder" => function (EquipmentRepository $equipmentRepository) use ($options) {
                    $qb = $equipmentRepository->createQueryBuilder('e');
                    $qb->leftJoin('e.reservations', 'r', 'WITH', 'r.dateLocation = :dateLocation');
                    $qb->andWhere('r.id IS NULL');
                    $qb->setParameter('dateLocation', $options['data']->getDateLocation());
                    return $qb;
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
