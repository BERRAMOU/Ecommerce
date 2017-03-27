<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Produits;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class CommandeController
 * @package Ecommerce\EcommerceBundle\Controller
 */
class CommandeController extends Controller
{

    public function facture()
    {

        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $generateur = $this->container->get('security.secure_random');
        $panier = $session->get('panier');
        $adresse = $session->get('adresse');

        //:les ingrédiants
        $commandes = array();
        $totalHT = 0;
        $totalTVA = 0;
        $facturation = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['facturation']);
        $livraison = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['livraison']);
        $produits = $em->getRepository('EcommerceBundle:Produits')->findByTableau(array_keys($session->get('panier')));

        ///Remplir la table commande
        foreach ($produits as $produit) {

            $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
            $prixTTC = ($produit->getPrix() * $panier[$produit->getId()]) / ($produit->getTva()->getMultiplicate());
            $totalHT += $prixHT;
            //$totalTTC += $prixTTC;

            if (!isset($commandes['tva']['%' . $produit->getTva()->getValeur()]))
                $commandes['tva']['%' . $produit->getTva()->getValeur()] = round($prixTTC - $prixHT, 2);

            else
                $commandes['tva']['%' . $produit->getTva()->getValeur()] += round($prixTTC - $prixHT, 2);

            $totalTVA += round($prixTTC - $prixHT, 2);

            $commandes['produit'][$produit->getId()] = array('reference' => $produit->getNom(),
                'quantite' => $panier[$produit->getId()],
                'prixHT' => round($produit->getPrix(), 2),
                'prixTTC' => round($produit->getPrix() / $produit->getTva()->getMultiplicate(), 2));

        }


        $commandes['livraison'] = array('nom' => $livraison->getNom(),
            'prenom' => $livraison->getPrenom(),
            'telephone' => $livraison->getTelephone(),
            'adresse' => $livraison->getAdresse(),
            'cp' => $livraison->getCp(),
            'pays' => $livraison->getPays(),
            'ville' => $livraison->getVille(),
            'complement' => $livraison->getComplement());

        $commandes['facturation'] = array('nom' => $facturation->getNom(),
            'prenom' => $facturation->getPrenom(),
            'telephone' => $facturation->getTelephone(),
            'adresse' => $facturation->getAdresse(),
            'cp' => $facturation->getCp(),
            'pays' => $facturation->getPays(),
            'ville' => $facturation->getVille(),
            'complement' => $facturation->getComplement());

        $commandes['prixHT'] = round($totalHT, 2);
        $commandes['prixTTC'] = round($totalHT + $totalTVA, 2);
        $commandes['token'] = bin2hex($generateur->nextBytes(20));


        return $commandes;


    }


    public function prepareCommandeAction()
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        if (!$session->has('commande')) {

            $commande = new Commandes();

        } else {
            $commande = $em->getRepository('EcommerceBundle:Commandes')->find($session->get('commande'));
        }


        $commande->setUtilisateur($this->container->get('security.context')->getToken()->getUser());
        $commande->setDate(new \DateTime());
        $commande->setValider(0);
        $commande->setReference(0);
        $commande->setCommande($this->facture());

        if (!$session->has('commande')) {
            $em->persist($commande);
            $session->set('commande', $commande);

        }

        $em->flush();

        return new Response($commande->getId());
    }


    ///simulation d'ApI Banque
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validationCommandeAction($id)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('EcommerceBundle:Commandes')->find($id);

        if (!$commande || $commande->getValider() == 1) throw  $this->createNotFoundException('la commande n\'existe pas ');

        $commande->setValider(1);
        $commande->setReference($this->container->get('setNewReference')->reference());//service
        $em->flush();

        $session->remove('adresse');
        $session->remove('panier');
        $session->remove('commande');


        //Ici le mail de validation
        $mailer =$this->container->get('mailer');
        $transport= \Swift_SmtpTransport::newInstance('smtp.gmail.com', 25)
                                                            ->setUsername('azedine.berramou@gmail.com')
                                                            ->setPassword('0635933321');

        $mailer= \Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance('Test')
            ->setSubject('Validation de votre commande')
            ->setFrom('azedine.berramou@gmail.com')
            ->setTo($commande->getUtilisateur()->getEmailCanonical())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->renderView('EcommerceBundle:Default:SwiftLayout/validationCommande.html.twig', array('utilisateur' => $commande->getUtilisateur())));

        $this->get('mailer')->send($message);

        $this->get('session')->getFlashBag()->add('success', 'votre commande est valide avec succes ');

        return $this->redirect($this->generateUrl('facture'));

    }


}
