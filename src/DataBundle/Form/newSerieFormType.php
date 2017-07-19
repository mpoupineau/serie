<?php

namespace DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class newSerieFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {        
        $builder
			->add('serie_name', TextType::class)
			->add('submit', SubmitType::class)
			->getForm();
    }
    
    public function getName()
    {        
        return 'acteurrecherche';
    }
}