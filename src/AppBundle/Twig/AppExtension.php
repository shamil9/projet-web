<?php
namespace AppBundle\Twig;

use AppBundle\Entity\Member;
use AppBundle\Entity\ProMember;

class AppExtension extends \Twig_Extension
{

    /**
     * Renvoi le type d'utilisateur pour ensuite être utilisé dans Twig
     * {{ if variable is member/proMember }}
     *
     * @return array
     */
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('member', function ( $user ) {
                return $user instanceof Member;
            }),
            new \Twig_SimpleTest('proMember', function ( $user ) {
                return $user instanceof ProMember;
            }),
        );
    }

    public function getName()
    {
        return 'app_extension';
    }
}
