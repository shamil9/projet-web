<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class EmailNotification extends Event
{
    public $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }
}