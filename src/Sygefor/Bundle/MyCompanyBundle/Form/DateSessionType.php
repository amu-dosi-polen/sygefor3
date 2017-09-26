<?php

/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/04/14
 * Time: 14:30.
 */
namespace Sygefor\Bundle\MyCompanyBundle\Form;

use Doctrine\ORM\EntityRepository;
use Sygefor\Bundle\MyCompanyBundle\Entity\DateSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DateSessionType.
 */
class DateSessionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateBegin', DateType::class, array(
                'label' => 'Date de début',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => true,
            ))
            ->add('dateEnd', DateType::class, array(
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
            ))
            ->add('scheduleMorn', null, array(
                'label' => "Horaires matin",
                'required' => false
            ))
            ->add('hourNumberMorn', TextType::class, array(
                'label'    => "Nombre d'heures matin",
                'required' => true,
                'attr'     => array(
                    'min' => 1,
                    'max' => 999,
                ),
            ))
            ->add('scheduleAfter', null, array(
                'label' => "Horaires après-midi",
                'required' => false
            ))
            ->add('hourNumberAfter', TextType::class, array(
                'label'    => "Nombre d'heures après-midi",
                'required' => true,
                'attr'     => array(
                    'min' => 1,
                    'max' => 999,
                ),
            ))
            ->add('place', null, array(
            'label' => "Lieu",
            'required' => false
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DateSession::class
        ));
    }
}
