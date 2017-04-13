<?php

/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 08/04/14
 * Time: 15:53.
 */
namespace Sygefor\Bundle\MyCompanyBundle\DataFixtures\ORM;

use Sygefor\Bundle\CoreBundle\DataFixtures\AbstractTermLoad;
use Sygefor\Bundle\TrainingBundle\Entity\Training\Term\Theme;

/**
 * Class LoadTheme.
 */
class LoadTheme extends AbstractTermLoad
{
    static $class = Theme::class;

    public function getTerms()
    {
        return array(
            'Applications de gestion',
            'Bureautique',
            'Communication - efficacité personnelle',
            'Enseignement et recherche',
            'Formations métiers - environnement professionnel',
            'Informatique et TIC',
            'Langues',
            'Management',
            'Preparation concours',
            'Santé et sécurité'
        );
    }
}
