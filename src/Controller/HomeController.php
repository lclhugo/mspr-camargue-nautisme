<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RentalLocationRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/location', name: 'app_location')]
    public function location(RentalLocationRepository $rentalLocationRepository): Response
    {
        $rentalLocations = $rentalLocationRepository->findAll();

        return $this->render('home/location.html.twig', [
            'rentalLocations' => $rentalLocations,
        ]);
    }
}