<?php


namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ProUserRegistrationController
 * @package AppBundle\Controller
 */
class ProUserRegistrationController extends BaseController
{

    /**
     * @Route("/pro/register", name="pro_user_registration")
     */
    public function registerAction()
    {
        $this->render(':Registration:register_form.html.twig');
    }
}
