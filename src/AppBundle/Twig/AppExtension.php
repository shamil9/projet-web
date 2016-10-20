<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('instanceof', array($this, 'isInstanceof')),
        );
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance) {
        return $var instanceof $instance;
    }

    public function getName()
    {
        return 'app_extension';
    }
}
