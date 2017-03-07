<?php

namespace WM\FilmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use WM\FilmBundle\Entity\Listing;
use WM\FilmBundle\Form\ListingType;
use Symfony\Component\HttpFoundation\Request;

class ListingController extends controller
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('WMFilmBundle:Listing');

        $listeFilms = $repo->findAll();

        return $this->render('WMFilmBundle:Listing:index.html.twig', array('listFilms' => $listeFilms));

    }

    public function ajoutAction(Request $request)
    {
        //on cree un film
        $film = new Listing();

        //on crée le formulaire
        $form = $this->createForm(ListingType::class, $film);

        $form->handleRequest($request);

        //si le form a été soumis
        if($form->isSubmitted())
        {
            //On enregistre le produit en bdd

            $em = $this->getDoctrine()
                ->getManager();

            $em->persist($film);
            $em->flush();

            return new Response('Film ajouté');
        }

        //on génère le HTML du formu crée
        $formView = $form->createView();

        return $this->render('WMFilmBundle:Listing:ajout.html.twig', array('form' => $formView));
    }

    public function editAction(Request $request, Listing $film )
    {
        //on crée le formulaire
        $form = $this->createForm(ListingType::class, $film);

        $form->handleRequest($request);

        //si le form a été soumis
        if($form->isSubmitted())
        {
            //On enregistre le produit en bdd

            $em = $this->getDoctrine()
                ->getManager();

            //cela est inutile car l'objet provient déjà de la bdd
//            $em->persist($film);
            $em->flush();

            return new Response('Film modifier');
        }

        //on génère le HTML du formu crée
        $formView = $form->createView();

        return $this->render('WMFilmBundle:Listing:edit.html.twig', array('form' => $formView));
    }

    public function deleteAction(Listing $id)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $film = $em->getRepository('WMFilmBundle:Listing')->find($id);

        $message = 'Ce film portant l\'id numéro ' .$film->getId().' a bien été supprimé, ';
        $em->remove($id);

        $em->flush();

        return new Response($message);

    }
}
