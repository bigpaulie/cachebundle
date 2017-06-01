<?php

namespace bigpaulie\CacheBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use bigpaulie\CacheBundle\Doctrine\NullObject;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /**
         * Example returning a NullObject as default for queries with no results.
         * The use of the third parameter silences the default Doctrine\ORM\NoResultException thrown
         * if no results are returned by the query.
         */
        $cached = $this->getDoctrine()->getRepository('BigpaulieCacheBundle:TestEntity')->find(1, 120, function (){
            return new NullObject();
        });

        $container = $this->get('service_container');
        var_dump($container->getParameter('bigpaulie_cache'));

        return $this->render('BigpaulieCacheBundle:Default:index.html.twig', [
            'cached' => $cached
        ]);
    }
}
