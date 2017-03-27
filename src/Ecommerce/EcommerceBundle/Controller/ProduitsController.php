<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Produits;
use Ecommerce\EcommerceBundle\Form\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ecommerce\EcommerceBundle\Entity\Categories;

class ProduitsController extends Controller
{

    ///Lister tous les prodits
    public function produitsAction($categories = null)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if ($categories != null)
            $findproduits = $em->getRepository('EcommerceBundle:Produits')->finbyCategorie($categories);

         else
             $findproduits = $em->getRepository('EcommerceBundle:Produits')->findBy(array('disponible' => 1));


        if ($session->has('panier'))
            $panier = $session->get('panier');

        else
            $panier = false;


        $produits =$this->get('knp_paginator') ->paginate($findproduits,$this->get('request')->query->get('page',1), 3);


        return $this->render('EcommerceBundle:Default:Produits/layout/produits.html.twig', array('produits' => $produits,
            'panier' => $panier));
    }

    ///presenter un produit specifie
    public function presentationAction(Produits $id)
    {

        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);

        if ($session->has('panier')) {

            $panier = $session->get('panier');

        } else {

            $panier = false;
        }

        if (!$produit) throw  $this->createNotFoundException('la pages que vous voulez n\'existe pas ');

        return $this->render('EcommerceBundle:Default:Produits/layout/presantation.html.twig', array('produit' => $produit,
            'panier' => $panier));
    }


    public function rechercheAction()
    {

        $form = $this->createForm(new RechercheType());

        return $this->render('EcommerceBundle:Default:recherche/modulesUsed/recherche.html.twig', array('form' => $form->createView()));
    }


    ///La methodes de traitement de recherche
    public function rechercheTraitementAction()
    {

        $form = $this->createForm(new RechercheType());

        if ($this->get('request')->getMethod() == 'POST') {

            $form->bind($this->get('request'));
            $em = $this->getDoctrine()->getManager();
            $produits = $em->getRepository('EcommerceBundle:Produits')->recherche($form['recherche']->getData());

        } else {

            throw  $this->createNotFoundException('la pages que vous voulez n\'existe pas ');
        }

        return $this->render('EcommerceBundle:Default:Produits/layout/produits.html.twig', array('produits' => $produits));
    }


}
