<?php

namespace Sygefor\Bundle\FrontBundle\Form;

use Doctrine\ORM\EntityRepository;
use Sygefor\Bundle\MyCompanyBundle\Form\TraineeType;
use Sygefor\Bundle\CoreBundle\Entity\Organization;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class ProfileType.
 */
class ProfileType extends TraineeType
{/**
 * @param FormBuilderInterface $builder
 * @param array                $options
 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        //$builder->remove('status');
        //$builder->remove('service');
        $builder->remove('isPaying');
        $builder->get('email')->setDisabled(true);
        $builder->get('firstName')->setDisabled(true);
        $builder->get('lastName')->setDisabled(true);
        $builder->get('title')->setDisabled(true);
        $builder->get('organization')->setDisabled(true);
        $builder->get('status')->setDisabled(true);
        $builder->remove('isActive');

        //$builder
        //    ->add('service', null, array(
        //        'required' => false,
        //       'label'    => 'Service',
        //    ))
        //    ->add('status', null, array(
        //        'required' => false,
        //        'label'    => 'Statut / fonction',
        //    ));
	$builder->get('publicType')->setDisabled(true);


        $builder->remove('institution');
    }

    /**
     * Add institution field depending organization.
     *
     * @param FormInterface $form
     * @param Organization  $organization
     */
    protected function addInstitutionField(FormInterface $form, $organization)
    {

    }
}
