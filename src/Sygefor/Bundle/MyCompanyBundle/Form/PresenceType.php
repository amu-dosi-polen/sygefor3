<?php

/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/04/14
 * Time: 14:30.
 */
namespace Sygefor\Bundle\MyCompanyBundle\Form;

use Doctrine\ORM\EntityRepository;
use Sygefor\Bundle\MyCompanyBundle\Entity\Presence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class PresenceType.
 */
class PresenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('morning', 'choice', array(
                'label' => "Matin",
                'required' => false,
                'choices' => array(
                    'Absent' => 'Absent',
                    'Présent' => 'Présent')
                ))
            ->add('afternoon', 'choice', array(
                'label' => "Après-midi",
                'required' => false,
                'choices' => array(
                    'Absent' => 'Absent',
                    'Présent' => 'Présent')
            ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Presence::class
        ));
    }

}
