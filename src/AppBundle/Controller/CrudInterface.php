<?php

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;

interface CrudInterface
{
    /**
     * Affichage de la liste complète
     */
    public function indexAction();

    /**
     * Nouveau enregistrement
     * @param Request $request
     * @return
     */
    public function newAction( Request $request );

    /**
     * Affichage individuel
     * @param $entity
     * @return
     */
    public function showAction( $entity );

    /**
     * Edition
     */
    public function editAction();

    /**
     * Mise à jour
     *
     * @param Request $request
     * @return mixed
     */
    public function updateAction( Request $request );

    /**
     * Suppression
     * @param Request $request
     * @return
     */
    public function destroyAction( Request $request );
}
