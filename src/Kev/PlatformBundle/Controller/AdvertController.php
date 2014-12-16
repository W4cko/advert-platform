<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Kev\PlatformBundle\Controller;

use Kev\PlatformBundle\Entity\AdvertSkill;
use Kev\PlatformBundle\Entity\Application;
use Kev\PlatformBundle\Entity\Image;
use Kev\PlatformBundle\Form\AdvertEditType;
use Kev\PlatformBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kev\PlatformBundle\Entity\Advert;


class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page "'.$page.'" inexistante.');
        }
        // Getting nb_per_page in app/config/parameters.yml
        $nbPerPage = $this->container->getParameter('nb_per_page');

        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository('KevPlatformBundle:Advert')->getAdverts($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts)/$nbPerPage);

        if ($page > $nbPages && $page !== 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('KevPlatformBundle:Advert:index.html.twig',
                array(
                    'listAdverts' => $listAdverts,
                    'nbPages'=>$nbPages,
                    'page'=>$page
                )
        );
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('KevPlatformBundle:Advert')->find($id);

        if (null === $advert){
            throw new NotFoundHttpException("L'annonce demandée (".$id.")n'existe pas");
        }
        $em = $this->getDoctrine()->getManager();
        $listAdvertSkills = $em->getRepository('KevPlatformBundle:AdvertSkill')->findByAdvert($advert);

        return $this->render('KevPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert, 'listApplications' => $advert->getApplications(), 'listAdvertSkills' => $listAdvertSkills
        ));
    }

    public function addAction(Request $request)
    {
        // Form
        $advert = new Advert();

        $form = $this->createForm(new AdvertType(), $advert);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirect($this->generateUrl('kev_platform_view', array('id' => $advert->getId())));
        }

        return $this->render('KevPlatformBundle:Advert:add.html.twig', array('form'=> $form->createView()));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('KevPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create(new AdvertEditType(), $advert);


        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirect($this->generateUrl('kev_platform_view', array('id' => $advert->getId())));

        }

        return $this->render('KevPlatformBundle:Advert:edit.html.twig',array(
        'advert' => $advert,'form' => $form->createView()));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('KevPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()){
            $em->remove($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');
            return $this->redirect($this->generateUrl('kev_platform_home'));
        }

        return $this->render('KevPlatformBundle:Advert:delete.html.twig',array(
            'advert' => $advert,'form' => $form->createView()));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('KevPlatformBundle:Advert')->findAll();

        return $this->render('KevPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}