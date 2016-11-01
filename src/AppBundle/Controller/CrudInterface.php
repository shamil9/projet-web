<?php

namespace AppBundle\Controller;


interface CrudInterface
{
    /**
     * Affichage de la liste complète
     */
    public function indexAction();

    /**
     * Nouveau enregistrement
     */
    public function newAction();

    /**
     * Affichage individuel
     */
    public function showAction();

    /**
     * Edition
     */
    public function editAction();

    public function updateAction();

    /**
     * Suppression
     */
    public function destroyAction();
}
