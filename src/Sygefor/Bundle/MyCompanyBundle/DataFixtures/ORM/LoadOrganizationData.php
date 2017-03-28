<?php

namespace Sygefor\Bundle\MyCompanyBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sygefor\Bundle\CoreBundle\DataFixtures\AbstractDataFixture;
use Sygefor\Bundle\CoreBundle\DataFixtures\AbstractTermLoad;
use Sygefor\Bundle\CoreBundle\Entity\Organization;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class LoadOrganizationData.
 */
class LoadOrganizationData extends AbstractTermLoad
{
    static $class = Organization::class;

    public function getTerms()
    {
        /*return array(
            array(
                'name'        => 'Conjecto',
                'code'        => 'conjecto',
                'address'     => '29 rue de Lorient',
                'zip'         => '35000',
                'city'        => 'Rennes',
                'email'       => 'contact@conjecto.com',
                'phoneNumber' => '(+33) 9 80 52 20 21',
                'faxNumber'   => '',
                'website'     => 'http://www.conjecto.com',
                'trainee_registrable' => true,
            ),
        );*/
        return array(
            array(
                'name'        => 'AMU-DRH',
                'code'        => 'amu-drh',
                'address'     => '58, bd Charles Livon',
                'zip'         => '13284',
                'city'        => 'Marseille Cedex 07',
                'email'       => 'drh-secretariat@univ-amu.fr',
                'phoneNumber' => '+33 491 396 500',
                'faxNumber'   => '',
                'website'     => 'http://www.univ-amu.fr',
                'trainee_registrable' => true,
            ),
        );
    }

    function getOrder()
    {
        return 0;
    }
}
