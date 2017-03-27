<?php

namespace Ecommerce\EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateursAdressesType extends AbstractType
{
    private  $em;

    /**
     * UtilisateursAdressesType constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
//        var_dump($em);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('adresse')
            ->add('cp',null , array('attr' => array('class' => 'cp')))
            ->add('ville','choice' , array('attr' => array('class' => 'ville')))
            ->add('pays')
            ->add('complement', null, array('required' => false))// ->add('utilisateur')
        ;

        $city = function (FormInterface $form , $cp ){

            $villeRegion = $this->em ->getRepository('AppBundle:Ville')->findBy(array('region' => $cp) );


            if($villeRegion){
                $villes =array();
                foreach ($villeRegion as $ville ){
                    $villes [] =$ville->getVille();
                }
            }else{
                $villes = null ;
            }

           $form->add('ville','choice' , array('attr' => array('class' => 'ville' ) , 'choices' => $villes ));

        };

        $builder->get('cp')->addEventListener(FormEvents::POST_SUBMIT,function (FormEvent $event ) use ($city){
            $city($event->getForm()->getParent(), $event->getForm()->getData());
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses'
        ));
    }
}
