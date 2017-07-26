<?php

namespace Sygefor\Bundle\MyCompanyBundle\Form;


use Sygefor\Bundle\MyCompanyBundle\Entity\Inscription;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sygefor\Bundle\InscriptionBundle\Form\BaseInscriptionType;
use Sygefor\Bundle\MyCompanyBundle\Entity\Term\ActionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Sygefor\Bundle\MyCompanyBundle\Form\PresenceType;

/**
 * Class InscriptionType.
 */
class InscriptionType extends BaseInscriptionType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motivation', TextareaType::class, array(
                'label' => 'Motivation',
                'required' => false
            ))
            ->add('actiontype', 'entity', array(
            'label' => 'Type de formation',
            'class' => ActionType::class
            ));


        parent::buildForm($builder, $options);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Inscription::class,
        ));
    }
}
