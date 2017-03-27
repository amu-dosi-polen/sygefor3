<?php

namespace Sygefor\Bundle\FrontBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class ValidType.
 */
class ValidType
{/**
 * @param FormBuilderInterface $builder
 * @param array                $options
 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valid', 'choice', array(
                'choices' => array('ok' => 'Avis favorable', 'nok' => 'Avis défavorable'),
                'expanded' => true,
                'multiple' => false
            ));

    }

}
