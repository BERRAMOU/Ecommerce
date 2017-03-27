<?php
namespace  Ecommerce\EcommerceBundle\Command;
use Ecommerce\EcommerceBundle\EcommerceBundle;
use Ecommerce\EcommerceBundle\Entity\Commandes;
use  Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use  Symfony\Component\Console\Input\InputArgument;
use  Symfony\Component\Console\Input\InputInterface;
use  Symfony\Component\Console\Input\InputOption;
use  Symfony\Component\Console\Output\OutputInterface;

class FacturesCommand extends ContainerAwareCommand {

    protected function configure(){

        $this->setName('ecommerce:facture')
            ->setDescription('ceci est un premier test')
            ->addArgument('date' , InputArgument::OPTIONAL,'Date des factures voulez  ');
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $date = new \DateTime();
        $em = $this->getContainer()->get('doctrine')->getManager();
        $factures = $em->getRepository('EcommerceBundle:Commandes')->byDateCommand($input->getArgument('date'));

        $output->writeln(count($factures) . ' facture(s) trouver ');

        if (count($factures) > 0 ){
            $dir = $date ->format('d-m-y __ h_i_s');
            mkdir('Facturation/'.$dir);

            foreach ($factures as $facture){

                $this->getContainer()->get('setNewFacture')->facture($facture)
                    ->Output('Facturation/'.$dir.'/facture'.$facture->getReference().'.pdf','F');

            }


        }


    }


}