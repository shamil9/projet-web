<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Sale;
use AppBundle\Form\SaleType;

/**
 * Sale controller.
 *
 * @Route("/prestataires/{slug}")
 */
class SaleController extends BaseController
{
    private $user;

    public function __construct()
    {
        $this->user = $this->getUser();
    }
    
    /**
     * Affiche toutes les promotions
     *
     * @Route("/", name="prestataires_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $sales = $this->em()->getRepository('AppBundle:Sale')->findAll();

        return $this->render('sale/index.html.twig', array(
            'sales' => $sales,
            'user' => $this->user,
        ));
    }

    /**
     * Creates a new Sale entity.
     *
     * @Route("/new", name="prestataires_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $sale = new Sale();
        $form = $this->createForm('AppBundle\Form\SaleType', $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sale);
            $em->flush();

            return $this->redirectToRoute('prestataires_show', array('id' => $sale->getId()));
        }

        return $this->render('sale/new.html.twig', array(
            'sale' => $sale,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sale entity.
     *
     * @Route("/{id}", name="prestataires_show")
     * @Method("GET")
     */
    public function showAction(Sale $sale)
    {
        $deleteForm = $this->createDeleteForm($sale);

        return $this->render('sale/show.html.twig', array(
            'sale' => $sale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sale entity.
     *
     * @Route("/{id}/edit", name="prestataires_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Sale $sale)
    {
        $deleteForm = $this->createDeleteForm($sale);
        $editForm = $this->createForm('AppBundle\Form\SaleType', $sale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sale);
            $em->flush();

            return $this->redirectToRoute('prestataires_edit', array('id' => $sale->getId()));
        }

        return $this->render('sale/edit.html.twig', array(
            'sale' => $sale,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Sale entity.
     *
     * @Route("/{id}", name="prestataires_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Sale $sale)
    {
        $form = $this->createDeleteForm($sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sale);
            $em->flush();
        }

        return $this->redirectToRoute('prestataires_index');
    }

    /**
     * Creates a form to delete a Sale entity.
     *
     * @param Sale $sale The Sale entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sale $sale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('prestataires_delete', array('id' => $sale->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
