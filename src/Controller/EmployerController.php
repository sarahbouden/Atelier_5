<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Form\EmployerType;
use App\Repository\EmployerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employer')]
class EmployerController extends AbstractController
{
    #[Route('/', name: 'app_employer_index', methods: ['GET'])]
    public function index(EmployerRepository $employerRepository): Response
    {
        return $this->render('employer/index.html.twig', [
            'employers' => $employerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_employer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employer = new Employer();
        $form = $this->createForm(EmployerType::class, $employer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employer);
            $entityManager->flush();

            return $this->redirectToRoute('app_employer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employer/new.html.twig', [
            'employer' => $employer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employer_show', methods: ['GET'])]
    public function show(Employer $employer): Response
    {
        return $this->render('employer/show.html.twig', [
            'employer' => $employer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employer $employer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployerType::class, $employer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_employer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employer/edit.html.twig', [
            'employer' => $employer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employer_delete', methods: ['POST'])]
    public function delete(Request $request, Employer $employer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($employer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employer_index', [], Response::HTTP_SEE_OTHER);
    }
}
