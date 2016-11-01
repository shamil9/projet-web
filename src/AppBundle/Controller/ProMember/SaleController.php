<?php

namespace AppBundle\Controller\ProMember;

use AppBundle\Entity\ProMember;
use AppBundle\Controller\BaseController;
use AppBundle\Form\SaleType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Sale;

/**
 * Sale controller.
 *
 * @Route("/prestataires/{slug}")
 */
class SaleController extends BaseController
{
    /**
     * Affiche toutes les promotions
     *
     * @Route("/", name="sales_index")
     * @param ProMember $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(ProMember $user)
    {
        return $this->render('sale/index.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Creates a new Sale entity.
     *
     * @Route("/new", name="sales_new")
     */
    public function newAction(Request $request)
    {
        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sale->setUser($this->getUser());
            $this->em()->persist($sale);
            $this->em()->flush();

            return $this->redirectToRoute('sales_index', array(
                'id' => $sale->getId(),
                'slug' => $this->getUser()->getSlug(),
            ));
        }

        return $this->render('sale/new.html.twig', array(
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sale entity.
     *
     * @Route("/{id}", name="prestataires_show")
     * @Method("GET")
     * @param Sale $sale
     * @return \Symfony\Component\HttpFoundation\Response
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
     * Affiche le formulaire d'edition d'une promotion
     *
     * @Route("/{id}/edit", name="sale_edit")
     * @param Request $request
     * @param Sale $sale
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Sale $sale)
    {
        $deleteForm = $this->createDeleteForm($sale);
        $editForm = $this->createForm('AppBundle\Form\SaleType', $sale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->persist($sale);
            $this->em()->flush();

            return $this->redirectToRoute('sale_edit', array(
                'id' => $sale->getId(),
                'slug' => $this->getUser()->getSlug(),
                ));
        }

        return $this->render('sale/edit.html.twig', array(
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une promotion
     *
     * @Route("/{id}", name="sale_delete")
     * @param Request $request
     * @param Sale $sale
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Sale $sale)
    {
        $form = $this->createDeleteForm($sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->remove($sale);
            $this->em()->flush();
        }

        return $this->redirectToRoute('pro_member_profile', [
            'slug' => $this->getUser()->getSlug(),
        ]);
    }

    /**
     * Formulaire de suppression d'une promotion
     *
     * @param Sale $sale The Sale entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sale $sale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sale_delete', array(
                'id' => $sale->getId(),
                'slug' => $this->getUser()->getSlug(),
                )
            ))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
