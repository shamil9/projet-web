<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\ProMember;
use AppBundle\Entity\Workshop;
use AppBundle\Form\WorkshopType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @param ProMember $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(ProMember $user)
    {
        return $this->render('workshop/index.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * Ajout d'un stage
     *
     * @Route("/ajouter", name="stage_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshop->setUser($this->getUser());
            $this->em()->persist($workshop);
            $this->em()->flush();

            return $this->redirectToRoute( 'user_profile', [ 'slug' => $this->getUser()->getSlug() ] );
        }

        return $this->render('workshop/new.html.twig', array(
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ));
    }


    /**
     * Mise à jour d'un stage
     *
     * @Route("/{id}/editer", name="stage_edit")
     * @param Request $request
     * @param Workshop $workshop
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @param Request $request
     * @param Workshop $workshop
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
