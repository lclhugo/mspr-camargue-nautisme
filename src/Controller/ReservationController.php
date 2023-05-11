<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\RentalLocation;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Repository\EquipmentRepository;
use App\Repository\RentalLocationRepository;
use App\Repository\ReservationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    //this route is used to show a form with only a date and a location,
    #[Route('/new', name: 'prereservation', methods: ['GET', 'POST'])]
    public function prereservation(RentalLocationRepository $rentalLocationRepository): Response
    {
        //get all the locations and send them to the view
        $locations = $rentalLocationRepository->findAll();
        return $this->render('reservation/prereservation.html.twig', [
            'locations' => $locations,
        ]);

    }
    //show a form with only a date and a location,
    #[Route('/', name: 'prereservation_save', methods: ['GET', 'POST'])]
    public function create(Request $request, ReservationRepository $reservationRepository): Response
    {
        $date = $request->request->get('location-date');
        $id = $request->request->get('location');
        $formattedDate = date('Y-m-d', strtotime($date));

        return $this->redirectToRoute('app_reservation_new', ['date' => $formattedDate, 'id' => $id]);
    }

    #[Route('/new/{date}/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new($date, $id, Request $request,  ReservationRepository $reservationRepository, EquipmentRepository $equipmentRepository, RentalLocationRepository $rentalLocationRepository): Response
    {
        //get the location and the date from the route
        $location = $rentalLocationRepository->find($id);
        $dateConverted = new \DateTime($date);

        //get all the equipments that are not reserved for the date and location
//        $equipments = $equipmentRepository->findAvailableEquipmentsByDateAndLocation($dateConverted, $location->getId());
        $reservation = new Reservation();
        $reservation->setLocation($location);
        $reservation->setDateLocation($dateConverted);


        //create the form
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        //if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            //redirect to the index
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        //render the view
        return $this->renderForm('reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form,
                    'date' => $date,
                    'id' => $id,
                ]);

    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
