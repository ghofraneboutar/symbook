<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LivresController extends AbstractController
{
    #[Route('/livres', name: 'app_livres')]
    public function index(): Response
    {
        return $this->render('livres/index.html.twig', [
            'controller_name' => 'LivresController',
        ]);
    }
    #[Route('/livres/create', name: 'app_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre=new Livre();
        $date=new \DateTime("2025-03-10");
        $livre->setTitre('Titre 3')
            ->setSlug('titre-3')
            ->setImage('https://picsum.photos/200/300')
            ->setResume('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque neque sequi sit! Culpa dolore, doloremque doloribus eaque eos excepturi id laudantium nostrum odit officiis repellat, sapiente vitae voluptates. Cupiditate, quo?')
            ->setIsbn('123-123-123-129')
            ->setDateEdition($date)
            ->setEditeur('eyrolles')
            ->setPrix(18);
        $em->persist($livre);
        $em->flush();
        return new Response("created new livre with id {$livre->getId()}");
    }
    #[Route('admin/livres/show/{id}', name: 'app_livres_show')]
    public function show(LivreRepository $rep,$id): Response
    {
        $livre=$rep->find($id);
        if(!$livre){
            throw $this->createNotFoundException("livre not found for id {$id}");
        }
        //dd($livre);
        return $this->render('livres/show.html.twig', ['livre' => $livre]);
    }
    #[Route('/admin/livres/show2', name: 'app_livres_show2')]
    public function show2(LivreRepository $rep): Response
    {
        $livre=$rep->findOneBy(['titre1'=>'titre1']);
        dd($livre);
    }
    #[Route('/admin/livres/show3', name: 'app_livres_show3')]
    public function show3(LivreRepository $rep): Response
    {
        $livre=$rep->findBy(['titre'=>'titre1'],['prix'=>'DESC'],['editeur'=>'eyrolles']);
        dd($livre);
    }
    #[Route('admin/livres/all', name: 'app_livres_all')]
    public function all(LivreRepository $rep, PaginatorInterface $paginator ,Request $request): Response
    {
        $livre = $paginator->paginate($rep->findAll(),
        $request->query->getInt('page', 1), /* page number */
        10 /* limit per page */
    );
        //$livre=$rep->findAll();
        //dd($livre);
        return $this->render('livres/all.html.twig', ['Livres' => $livre]);
    }
    #[Route('/admin/livres/delete', name: 'app_livres_delete')]
    public function delete(Livre $livre ,LivreRepository $rep,EntityManagerInterface $em): Response
    {
        //$livre=$rep->find();
        $em->remove($livre);
        $em->flush();
        dd($livre);
    }
    #[Route('/admin/livres/update/{id}', name: 'app_livres_update')]
    public function update(Livre $livre, EntityManagerInterface $em,$id): Response
    {
        //$livre=$rep->find($id);
        $nouveauP=$livre->getPrix()*1+1;
        $livre->setPrix($nouveauP);
        $em->persist($livre);
        $em->flush();
        dd($livre);
    }

}
