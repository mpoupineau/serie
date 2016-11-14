<?php

namespace Serie\DataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class getFormCollectedForm extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {        
        $builder->add('motcle', 'text', array('label' => 'Mot-clé'));
    }
    
    public function getName()
    {        
        return 'acteurrecherche';
    }
}