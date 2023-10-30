<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinmaxType;
use App\Form\SearchType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
   public $authors = [
        ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
        ['id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
        ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
    ];  
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showauthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route('/list', name: 'app_list')]
    public function list(): Response
    {
        return $this->render('author/show.html.twig', [
            'authors' => $this->authors,
        ]);  
    }
    #[Route('/showbyidauthor/{id}', name: 'showbyidauthor')]
public function showbyidauthor($id): Response
{
    $author = null;
        foreach ($this->authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            }
        }

        return $this->render('author/showauthor.html.twig', [
            'author' => $author
        ]);
}
#[Route('/showDBauthor', name: 'showDBauthor')]
    public function showDBauthor(AuthorRepository $authorRepository,Request $req): Response
    {
        $x=$authorRepository->orderByemail();
        /*$form=$this->createForm(SearchType::class);
        $form->handleRequest($req);*/
        $form=$this->createForm(MinmaxType::class);
        $form->handleRequest($req);
        //$x=$authorRepository->SearchByalpha();
        if($form->isSubmitted() && $form->isValid()) {
            $min=$form->get('min')->getData();
            $max=$form->get('max')->getData();
           // var_dump($datainput);
          $authors=$authorRepository->minmax($min,$max);
           return $this->renderForm('author/showDBauthor.html.twig', [
            'authors' => $authors,
            'f'=>$form,
        ]);
        }
        return $this->renderForm('author/showDBauthor.html.twig', [
            'authors' => $x,
            'f'=>$form,
        ]);
    }
    #[Route('/showDBauthorByemail', name: 'showDBauthorByemail')]
    public function showDBauthorByemail(AuthorRepository $authorRepository,Request $req): Response
    {
        $x=$authorRepository->orderByemail();
           return $this->render('author/showDBauthorByemail.html.twig', [
            'authors' => $x,
        ]);
    }
    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $manager,Request $req, AuthorRepository $authorRepository): Response
{
    $em = $manager->getManager();
    $author = new Author();
    $form = $this->createForm(AuthorType::class, $author);
    
    $form->handleRequest($req);
    
    if ($form->isSubmitted() && $form->isValid()) {
      
        $em->persist($author);
        $em->flush();
        //return $this->redirectToRoute('showDBauthor.html.twig');
    }

    return $this->renderForm('author/add.html.twig', [
        'f' => $form, 
    ]);
}
#[Route('/editauthor/{id}', name: 'editauthor')]
public function editauthor($id, ManagerRegistry $manager, AuthorRepository $authorrepo, Request $req): Response
{
    // var_dump($id) . die();

    $em = $manager->getManager();
    $idData = $authorrepo->find($id);
    // var_dump($idData) . die();
    $form = $this->createForm(AuthorType::class, $idData);
    $form->handleRequest($req);

    if ($form->isSubmitted() and $form->isValid()) {
        $em->persist($idData);
        $em->flush();

        return $this->redirectToRoute('showDBauthor');
    }

    return $this->renderForm('author/edit.html.twig', [
        'form' => $form
    ]);
}
#[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id, ManagerRegistry $managerRegistry,AuthorRepository $authorRepository): Response
    {
        $em=$managerRegistry->getManager();
        $idR=$authorRepository->find($id);
        $em->remove($idR);
        $em->flush();

        return $this->redirectToRoute('showDBauthor');
    }
    #[Route('/showbook/{id}', name: 'showbook')]
        public function showbook($id,AuthorRepository $authorRepository): Response
        {
            $Books=$authorRepository->SearchById($id);
            var_dump($Books);
            //$x=$authorRepository->SearchByalpha();
            return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
        }

}
