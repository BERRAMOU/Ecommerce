<?php

namespace Pages\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  Pages\PagesBundle\Entity;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PagesController extends Controller
{

    public function menuAction()
    {

        $em = $this->getDoctrine()->getManager();

        $pages = $em->getRepository('PagesBundle:Pages')->findAll();

        return $this->render('PagesBundle:Default:pages/modulesUsed/menu.html.twig', array('pages' => $pages));
    }

    public function pageAction($slug)
    {

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('PagesBundle:Pages')->findOneBySlug($slug);

        if (!$page) throw  $this->createNotFoundException('la pages que vous voulez n\'existe pas ');

        return $this->render('PagesBundle:Default:pages/layout/pages.html.twig', array('page' => $page));
    }
}
