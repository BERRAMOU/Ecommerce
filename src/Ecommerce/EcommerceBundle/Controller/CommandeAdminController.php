<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Produits;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class CommandeAdminController extends Controller{


    public function commandesAction(){
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository('EcommerceBundle:Commandes')->findAll();

        return $this->render('EcommerceBundle:Administration:commandes/layout/index.html.twig', array('commandes' =>$commandes));
    }


    public function showFactureAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')->find($id);

        if (!$facture) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            return $this->redirect($this->generateUrl('adminCommandes'));
        }

        $this->container->get('SetNewFacture')->facture($facture)->Output('Facture.pdf');
        $response = new  Response();
        $response->headers->set('Content-Type' , 'application/pdf');
//        $response->send();
        return $response;
    }



}
