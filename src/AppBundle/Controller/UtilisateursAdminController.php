<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ecommerce\EcommerceBundle\Entity\Produits;
use Ecommerce\EcommerceBundle\Form\ProduitsType;


class UtilisateursAdminController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateurs = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('AppBundle:Administration:Utilisateurs/layout/index.html.twig',
            array('utilisateurs' => $utilisateurs));
    }

    public function utilisateurAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository('AppBundle:User')->find($id);
        $route=$this->container->get('request')->get('_route');

        if ( $route == 'adminAdressesUtilisateurs')
        return $this->render('AppBundle:Administration:Utilisateurs/layout/adresses.html.twig',array('utilisateur' => $utilisateur,));

        else if ($route == 'adminFacturesUtilisateurs')
            return $this->render('AppBundle:Administration:Utilisateurs/layout/factures.html.twig',array('utilisateur' => $utilisateur,));

        else
            throw $this->createNotFoundException('la vue n\'existe pas ');
    }


}
