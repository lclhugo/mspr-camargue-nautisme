<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Equipment;
use App\Entity\RentalLocation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EquipmentType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('price')
            ->add('description')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Choisir son type dequipement' => null,
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => $this->getCategoryChoices(),
                'choice_label' => function ($value, $key, $index) {
                    if (!$value) {
                        return 'Choisir son type dequipement';
                    }

                    return $value->getName();
                }
            ])

            ->add('rentalLocation', ChoiceType::class, [
                'choices' => [
                    'Choisis un point de location' => null,
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => $this->getLocation(),
                'choice_label' => function ($value, $key, $index) {
                    if (!$value) {
                        return 'Choisis un point de location';
                    }

                    return $value->getAddress();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }

    private function getCategoryChoices(): array
    {
        $choices = [];

        $categoryChoices = $this->entityManager
            ->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);

        foreach ($categoryChoices as $categoryChoice) {
            $choices[$categoryChoice->getName()] = $categoryChoice;
        }

        return $choices;
    }

    private function getLocation(): array
    {
        $choices = [];

        $rentalLocations = $this->entityManager
            ->getRepository(RentalLocation::class)
            ->findBy([], ['address' => 'ASC']);

        foreach ($rentalLocations as $rentalLocation) {
            $choices[$rentalLocation->getAddress()] = $rentalLocation;
        }

        return $choices;
    }
}
