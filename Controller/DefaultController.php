<?php

namespace bigpaulie\CacheBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BigpaulieCacheBundle:Default:index.html.twig');
    }
}
