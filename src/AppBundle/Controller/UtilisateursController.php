<?php

namespace AppBundle\Controller;

use Ecommerce\EcommerceBundle\EcommerceBundle;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class UtilisateursController extends Controller
{
    public  function villesAction(Request $request , $cp ){

      if ($request->isXmlHttpRequest()){
        $em = $this->getDoctrine()->getManager();
        $villeRegion = $em ->getRepository('AppBundle:Ville')->findBy(array('region' => $cp) );


        if($villeRegion){
            $villes =array();

            foreach ($villeRegion as $ville ){

                $villes [] =$ville->getVille();
            }


        }else{

            $villes = null ;
        }

        $response = new JsonResponse();

        return $response->setData(array('ville' => $villes )) ;

    }else{
        throw new  \Exception('Erreuur ');
      }
    }


    public function facturesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em ->getRepository('EcommerceBundle:Commandes')->byFacture($this->getUser());

        return $this->render('AppBundle:Default:layout/facture.html.twig', array( 'factures' => $factures));
    }

    public function facturePDFAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('EcommerceBundle:Commandes')->findOneBy(array('utilisateur' => $this->getUser(),
            'valider' => 1,
            'id' => $id));

        if (!$facture) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');
            return $this->redirect($this->generateUrl('facutres'));
        }
        $this->container->get('SetNewFacture')->facture($facture)->Output('Facture.pdf');
        $response = new  Response();
        $response->headers->set('Content-Type' , 'application/pdf');

        return $response;
    }



}
