<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Kev\PlatformBundle\Controller;

use Kev\PlatformBundle\Entity\Application;
use Kev\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kev\PlatformBundle\Entity\Advert;


class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony2',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
        );

        return $this->render('KevPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
    }

    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('KevPlatformBundle:Advert');

        $advert = $repository->find($id);

        if (null === $advert){
            throw new NotFoundHttpException("L'annonce demandée (".$id.")n'existe pas");
        }
        $em = $this->getDoctrine()->getManager();
        $listApplications = $em->getRepository('KevPlatformBundle:Application')->findBy(array('advert' => $advert));

        return $this->render('KevPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert, 'listApplications' => $listApplications
        ));
    }

    public function addAction(Request $request)
    {

        // Ajout annonce
        $advert = new Advert();
        $advert->setTitle('RechercheVomi');
        $advert->setAuthor('Kevv');
        $advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Lyon BLA");
        $date = new \DateTime();
        $advert->setDate($date);

        // ajout image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');
        $advert->setImage($image);

        // Ajout de 2 candidatures
        $application1 = new Application();
        $application1->setAuthor('Veutjob');
        $application1->setAdvert($advert);
        $application1->setContent('Je veut ce boulot putin :p');
        $application2 = new Application();
        $application2->setAuthor('Veutjob2');
        $application2->setAdvert($advert);
        $application2->setContent('Je veut ce boulot putin :p');


        // Entity manager
        $em = $this->getDoctrine()->getManager();

        $em->persist($advert);
        $em->persist($application2);
        $em->persist($application1);


        $em->flush();

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('kev_platform_add', array('id' => $advert->getId())));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('KevPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        // Même mécanisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => 5)));
        }

        $advert = array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        $em = $this->getDoctrine()->getManager();


        return $this->render('KevPlatformBundle:Advert:edit.html.twig',array(
        'advert' => $advert));
    }

    public function deleteAction($id)
    {
        return $this->render('KevPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction()
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('KevPlatformBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }
}