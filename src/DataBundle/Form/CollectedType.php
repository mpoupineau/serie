<?php

namespace DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class CollectedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rated',HiddenType::class)
            ->add('seasonSeen',HiddenType::class)
			->add('episodeSeen',HiddenType::class)
            ->add('follow', CheckboxType::class, array('label' => 'Suivre cette série'))
            ->add('alertFirstEpisode', CheckboxType::class, array('label' => 'Premier de saison'))
            ->add('alertLastEpisode', CheckboxType::class, array('label' => 'Dernier de saison'))
            ->add('alertEachEpisode', CheckboxType::class, array('label' => 'Chaque épisode'))
            ->add('comment')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DataBundle\Entity\Collected'
        ));
    }
}
