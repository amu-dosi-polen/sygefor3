<?php

/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 15/04/14
 * Time: 14:30.
 */
namespace Sygefor\Bundle\MyCompanyBundle\Form;

use Doctrine\ORM\EntityRepository;
use Sygefor\Bundle\MyCompanyBundle\Entity\Module;
use Sygefor\Bundle\TrainingBundle\Entity\Session\AbstractSession;
use Sygefor\Bundle\MyCompanyBundle\Entity\Session;
use Sygefor\Bundle\TrainingBundle\Form\BaseSessionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SessionType.
 */
class DateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateBegin', DateType::class, array(
                'label'    => 'Date de début',
                'widget'   => 'single_text',
                'format'   => 'dd/MM/yyyy',
                'required' => true,
            ))
            ->add('dateEnd', DateType::class, array(
                'label'    => 'Date de fin',
                'widget'   => 'single_text',
                'format'   => 'dd/MM/yyyy',
                'required' => false,
            ))
            ->add('timeBegin', null, array(
                'label'    => "Heure de début",
                'required' => false
            ))
            ->add('timeEnd', null, array(
                'label'    => "Heure de fin",
                'required' => false
            ));

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Session::class,
        ));
    }
}
