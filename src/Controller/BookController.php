<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\SarraType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/showDBbook', name: 'showDBbook')]
    public function showDBbook(BookRepository $bookRepository,Request $req): Response
    {
        $DB=$bookRepository->orderByauthor();
        $form=$this->createForm(SarraType::class);
        $form->handleRequest($req);
        $number=$bookRepository->nbrBooksSC();
        if($form->isSubmitted() && $form->isValid()){
            $ref=$form->get('ref')->getData();
            $book=$bookRepository->SearchByref($ref);
           return $this->renderForm('book/showDBbook.html.twig', [
            'books' => $book,
            'f'=>$form,
            'nbr'=>$number,
        ]);
        }
        return $this->renderForm('book/showDBbook.html.twig', [
            'books' => $DB,
            'f'=>$form,
            'nbr'=>$number,

        ]);
    }
    #[Route('/showDBbookByYandNbr', name: 'showDBbookByYandNbra')]
    public function showDBbookByYandNbr(BookRepository $bookRepository,Request $req): Response
    {
        $DB=$bookRepository->orderByYearAndNbr();
        $form=$this->createForm(SarraType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $ref=$form->get('ref')->getData();
            $book=$bookRepository->SearchByref($ref);
           return $this->renderForm('book/showDBbookByYandNbr.html.twig', [
            'books' => $book,
            'f'=>$form,
        ]);
        }
        return $this->renderForm('book/showDBbookByYandNbr.html.twig', [
            'books' => $DB,
            'f'=>$form,

        ]);
    }
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $managerRegistry,Request $req): Response
    {
        $em=$managerRegistry->getManager();
        $book=new Book();
        $form=$this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()) { 
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showDBbook');
        }
        return $this->renderForm('book/addBook.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/editbook/{ref}', name: 'editbook')]
    public function editbook($ref, BookRepository $bookRepository,ManagerRegistry $managerRegistry,Request $req): Response
    {
       // var_dump($id). die();
        $em=$managerRegistry->getManager();
        $book=$bookRepository->find($ref);
        $form=$this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()) { 
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showDBbook');
        }
        return $this->renderForm('book/editbook.html.twig', [
            'x' => $form,
        ]);
    }

    #[Route('/deletebook/{ref}', name: 'deletebook')]
    public function deletebook($ref,BookRepository $bookRepository,ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $book=$bookRepository->find($ref);
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('showDBbook');
        
    }
}
