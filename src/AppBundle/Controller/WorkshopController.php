<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Workshop;
use AppBundle\Form\WorkshopType;

/**
 * Workshop controller.
 *
 * @Route("/prestataires/{slug}/stages")
 */
class WorkshopController extends BaseController
{
    /**
     * Affiche tous les stages
     *
     * @Route("/", name="stage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('workshop/index.html.twig', array(
            'user' => $this->getUser()
        ));
    }

    /**
     * Ajout d'un stage
     *
     * @Route("/new", name="stage_new")
     */
    public function newAction(Request $request)
    {
        $workshop = new Workshop();
        $form = $this->createForm('AppBundle\Form\WorkshopType', $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshop->setUser($this->getUser());
            $this->em()->persist($workshop);
            $this->em()->flush();

            return $this->redirectToRoute('pro_user_profile', ['slug' => $this->getUser()->getSlug()]);
        }

        return $this->render('workshop/new.html.twig', array(
            'workshop' => $workshop,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ));
    }


    /**
     * Mise à jour d'un stage
     *
     * @Route("/{id}/edit", name="stage_edit")
     */
    public function editAction(Request $request, Workshop $workshop)
    {
        $deleteForm = $this->createDeleteForm($workshop);
        $editForm = $this->createForm('AppBundle\Form\WorkshopType', $workshop);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workshop);
            $em->flush();

            return $this->redirectToRoute('stage_edit', array(
                'id' => $workshop->getId(),
                'slug' => $this->getUser()->getSlug()
            ));
        }

        return $this->render('workshop/edit.html.twig', array(
            'user' => $this->getUser(),
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime un stage
     *
     * @Route("/{id}", name="stage_delete")
     */
    public function deleteAction(Request $request, Workshop $workshop)
    {
        $form = $this->createDeleteForm($workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($workshop);
            $em->flush();
        }

        return $this->redirectToRoute('stage_index', ['slug' => $this->getUser()->getSlug()]);
    }

    /**
     * Formulaire de suppression d'un stage
     *
     * @param Workshop $workshop The Workshop entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Workshop $workshop)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stage_delete', array(
                'id' => $workshop->getId(),
                'slug' => $this->getUser()->getSlug()
                )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
