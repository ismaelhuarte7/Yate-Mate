<?php

namespace App\Controller;

use App\Entity\ReservaAmarra;
use App\Form\ReservaAmarraType;
use App\Repository\ReservaAmarraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reserva/amarra')]
class ReservaAmarraController extends AbstractController
{
    #[Route('/', name: 'app_reserva_amarra_index', methods: ['GET'])]
    public function index(ReservaAmarraRepository $reservaAmarraRepository): Response
    {
        return $this->render('reserva_amarra/index.html.twig', [
            'reserva_amarras' => $reservaAmarraRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reserva_amarra_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $usuario = $this->getUser();
        $reservaAmarra = new ReservaAmarra();
        $form = $this->createForm(ReservaAmarraType::class, $reservaAmarra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservaAmarra->setSolicitante($usuario);
            $reservaAmarra->getPublicacionAmarra()->setEstaAlquilada(true);
            $entityManager->persist($reservaAmarra);
            $entityManager->flush();

            // Añadir mensaje flash de éxito
            $this->addFlash('success', 'Has realizado una reserva de amarra.');

            return $this->redirectToRoute('app_publicacion_amarra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reserva_amarra/new.html.twig', [
            'reserva_amarra' => $reservaAmarra,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_reserva_amarra_show', methods: ['GET'])]
    public function show(ReservaAmarra $reservaAmarra): Response
    {
        return $this->render('reserva_amarra/show.html.twig', [
            'reserva_amarra' => $reservaAmarra,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reserva_amarra_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservaAmarra $reservaAmarra, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservaAmarraType::class, $reservaAmarra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reserva_amarra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reserva_amarra/edit.html.twig', [
            'reserva_amarra' => $reservaAmarra,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reserva_amarra_delete', methods: ['POST'])]
    public function delete(Request $request, ReservaAmarra $reservaAmarra, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservaAmarra->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reservaAmarra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reserva_amarra_index', [], Response::HTTP_SEE_OTHER);
    }
}
