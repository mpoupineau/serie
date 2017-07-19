<?php

namespace DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('serie', 'PUGX\AutocompleterBundle\Form\Type\AutocompleteType', array('class' => 'DataBundle:Serie'))
        ;
        /*$builder
            ->add('idDB')
            ->add('name')
            ->add('actors')
            ->add('airsDayOfWeek')
            ->add('firstAired', 'date')
            ->add('genre')
            ->add('language')
            ->add('overview')
            ->add('rating')
            ->add('runtime')
            ->add('status')
            ->add('banner')
            ->add('fanart')
            ->add('poster')
            ->add('lastUpdated')
        ;*/
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DataBundle\Entity\Serie'
        ));
    }
}
