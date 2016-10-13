<?php


namespace AppBundle\Component\Form\Extention\Core\Type;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class DateTimeType extends \Symfony\Component\Form\Extension\Core\Type\DateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['widget'] = $options['widget'];

        // Change the input to a HTML5 datetime input if
        //  * the widget is set to "single_text"
        //  * the format matches the one expected by HTML5
        //  * the html5 is set to true
        if ($options['html5'] && 'single_text' === $options['widget'] && self::HTML5_FORMAT === $options['format']) {
            $view->vars['type'] = 'datetime-local';
        }
    }
}
