<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\EcommerceBundle;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use  Ecommerce\EcommerceBundle\Repository\ProduitsRepository;


class PanierController extends Controller
{


    public function menuAction()
    {

        $session = $this->getRequest()->getSession();

        if (!$session->has('panier')) {

            $articles = 0;

        } else {

            $articles = count($session->get('panier'));
        }

        return $this->render('EcommerceBundle:Default:panier/modulesUsed/menu.html.twig', array('articles' => $articles));
    }

    public function ajouterAction($id)
    {

        $session = $this->getRequest()->getSession();

        if (!$session->has('panier')) {

            $session->set('panier', array());

        }

        $panier = $session->get('panier');


        if (array_key_exists($id, $panier)) {

            if ($this->getRequest()->query->get('qte') != null) {

                $panier[$id] = $this->getRequest()->query->get('qte');
                $this->get('session')->getFlashBag()->add('success', 'quantité modifier avec succee  ');
            }

        } else {

            if ($this->getRequest()->query->get('qte') != null) {

                $panier[$id] = $this->getRequest()->query->get('qte');

            } else {

                $panier[$id] = 1;
                $this->get('session')->getFlashBag()->add('success', 'l\'article est bien Ajoutée  ');

            }

        }
        $session->set('panier', $panier);

        return $this->redirect($this->generateUrl('panier'));
    }


    public function supprimerAction($id)
    {
        $session = $this->getRequest()->getSession();

        $panier = $session->get('panier');

        if (array_key_exists($id, $panier)) {

            unset($panier[$id]);
            $session->set('panier', $panier);

            $this->get('session')->getFlashBag()->add('success', 'l\'article est bien supprimée ');
        }


        return $this->redirect($this->generateUrl('panier'));
    }

    public function panierAction()
    {

        $session = $this->getRequest()->getSession();

        if (!$session->has('panier')) $session->set('panier', array());

        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('EcommerceBundle:Produits')->findByTableau(array_keys($session->get('panier')));

        ///$session->set('panier',$panier);

        return $this->render('EcommerceBundle:Default:panier/layout/panier.html.twig',
            array('produits' => $produits,
                'panier' => $session->get('panier')));
    }


    public function livraisonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new UtilisateursAdresses();
        $utilisateur = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new UtilisateursAdressesType($em), $entity);

        if ($this->get('request')->getMethod() == 'POST') {
            
            $form->handleRequest($this->getRequest());
            
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $entity->setUtilisateur($utilisateur);
                $em->persist($entity);
                $em->flush();
                
                return $this->redirect($this->generateUrl('livraison'));
            }


        }

        return $this->render('EcommerceBundle:Default:panier/layout/livraison.html.twig', array('form' => $form->createView(),
            'utilisateur' => $utilisateur));
    }


    public function adresseSuppressionAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($id);

        if ($this->container->get('security.context')->getToken()->getUser() != $entity->getUtilisateur() || !$entity) {
            return $this->redirect($this->generateUrl('livraison'));

        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('livraison'));
    }


    public function setLivraisonOnSession()
    {

        $sesseion = $this->getRequest()->getSession();

        if (!$sesseion->has('adresse')) $sesseion->set('adresse', array());
        $adresse = $sesseion->get('adresse');

        if ($this->getRequest()->request->get('livraison') != null && $this->getRequest()->request->get('facturation') != null) {
            $adresse['livraison'] = $this->getRequest()->request->get('livraison');
            $adresse['facturation'] = $this->getRequest()->request->get('facturation');

        } else {
            return $this->redirect($this->generateUrl('validation'));
        }

        $sesseion->set('adresse', $adresse);

        return $this->redirect($this->generateUrl('validation'));
    }


    public function validationAction()
    {

        $em = $this->getDoctrine()->getManager();

        if ($this->get('request')->getMethod() == 'POST')
            $this->setLivraisonOnSession();

        $prepareCommande = $this->forward('EcommerceBundle:Commande:prepareCommande');
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($prepareCommande->getContent());

        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig', array('commande' => $commande));

    }


}
