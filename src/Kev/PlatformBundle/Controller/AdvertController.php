<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Kev\PlatformBundle\Controller;

use Kev\PlatformBundle\Entity\AdvertSkill;
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

        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('KevPlatformBundle:Advert')->findAll();

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
        // Horrible car on fait une requete par iteration, methode pour l'exemple...
        $listAdvertSkills = $em->getRepository('KevPlatformBundle:AdvertSkill')->findBy(array('advert' => $advert));

        return $this->render('KevPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert, 'listApplications' => $listApplications, 'listAdvertSkills' => $listAdvertSkills
        ));
    }

    public function addAction(Request $request)
    {

        // Ajout annonce
        $advert = new Advert();
        $advert->setTitle('Recherche jobbb de fou');
        $advert->setAuthor('Kevv');
        $advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Lyon BLA");
        $date = new \DateTime();
        $advert->setDate($date);

        // ajout image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');
        $advert->setImage($image);

        // Ajout skills
        $em = $this->getDoctrine()->getManager();

        $listSkills = $em->getRepository('KevPlatformBundle:Skill')->findAll();
        foreach ($listSkills as $skill) {
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel('Expert');
            $em->persist($advertSkill);

        }


        // Ajout de 2 candidatures
        $application1 = new Application();
        $application1->setAuthor('Veutjob');
        $application1->setAdvert($advert);
        $application1->setContent('Je veut ce boulot xd :p');
        $application2 = new Application();
        $application2->setAuthor('Veutjob2');
        $application2->setAdvert($advert);
        $application2->setContent('Je veut ce boulot :p');

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
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('KevPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listCategories = $em->getRepository('KevPlatformBundle:Category')->findAll();

        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        $em->flush();

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirect($this->generateUrl('oc_platform_view'));
        }

        return $this->render('KevPlatformBundle:Advert:edit.html.twig',array(
        'advert' => $advert,'id' => $id));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('KevPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        $em->flush();

        return $this->render('KevPlatformBundle:Advert:edit.html.twig',array(
            'advert' => $advert,'id' => $id));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('KevPlatformBundle:Advert')->findAll();

        return $this->render('KevPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}