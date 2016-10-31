<?php


namespace AppBundle\Form\Model;


use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(message="Ancien mot de passe incorrect")
     */
    protected $currentPassword;
}
