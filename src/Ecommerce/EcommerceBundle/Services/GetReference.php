<?php
namespace Ecommerce\EcommerceBundle\Services;

use Ecommerce\EcommerceBundle\EcommerceBundle;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use Ecommerce\EcommerceBundle\Repository\CommandesRepository;
use Symfony\Component\Security\Core\SecurityContextInterface;


class GetReference {


    public function __construct($securitContext,$entityManager){

    $this->securityContext = $securitContext;
    $this->em = $entityManager;

}


    public function reference(){
        $reference = $this->em->getRepository('EcommerceBundle:Commandes')->findOneBy( array('valider'=>1) ,array('id' => 'DESC') , 1 , 1);

        if (!$reference)
            return 1;
        else
            return $reference->getReference()+1;
        }



}