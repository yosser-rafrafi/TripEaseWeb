<?php
namespace App\Controller;

use App\Entity\Transport;
use App\Form\TransportType;
use App\Repository\TransportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/manager/transport')]
final class TransportController extends AbstractController
{
    #[Route('/', name: 'app_transport_index', methods: ['GET'])]
    public function index(TransportRepository $transportRepository, Request $request): Response
    {
        $selectedAgency = $request->query->get('transport_agence');
        $selectedTransportType = $request->query->get('transport_type');
        $transports = $transportRepository->findAll();

        return $this->render('back/manager/transport/index.html.twig', [
            'transports' => $transports,
            'selectedAgency' => $selectedAgency,
            'selectedTransportType' => $selectedTransportType,
        ]);
    }

    #[Route('/new', name: 'app_transport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transport = new Transport();
        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        $googleMapsApiKey = $this->getParameter('google_maps_api_key');
        $latitude = 36.8065;
        $longitude = 10.1815;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transport);
            $entityManager->flush();

            return $this->redirectToRoute('app_transport_index');
        }

        return $this->render('back/manager/transport/new.html.twig', [
            'form' => $form->createView(),
            'google_maps_api_key' => $googleMapsApiKey,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'map_center' => "$latitude,$longitude",
            'map_zoom' => 12,
        ]);
    }

    #[Route('/{id}', name: 'app_transport_show', methods: ['GET'])]
    public function show(Transport $transport): Response
    {
        return $this->render('back/manager/transport/show.html.twig', [
            'transport' => $transport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transport $transport, EntityManagerInterface $entityManager): Response
    {
        if (!$transport) {
            throw $this->createNotFoundException('Transport not found');
        }

        $form = $this->createForm(TransportType::class, $transport);
        $form->handleRequest($request);

        $googleMapsApiKey = $this->getParameter('google_maps_api_key');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        $latitude = $transport->getLatitude();
        $longitude = $transport->getLongitude();

        return $this->render('back/manager/transport/edit.html.twig', [
            'transport' => $transport,
            'form' => $form->createView(),
            'google_maps_api_key' => $googleMapsApiKey,
            'map_center' => $latitude . ',' . $longitude,
            'map_zoom' => 12,
            'button_label' => 'Update',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    #[Route('/{id}', name: 'app_transport_delete', methods: ['POST'])]
    public function delete(Request $request, Transport $transport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $transport->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transport_index', [], Response::HTTP_SEE_OTHER);
    }
}
