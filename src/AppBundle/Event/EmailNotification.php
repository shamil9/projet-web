<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class EmailNotification extends Event
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}