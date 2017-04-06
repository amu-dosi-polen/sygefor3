<?php

/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 08/04/14
 * Time: 15:53.
 */
namespace Sygefor\Bundle\MyCompanyBundle\DataFixtures\ORM;

use Sygefor\Bundle\CoreBundle\DataFixtures\AbstractTermLoad;
use Sygefor\Bundle\MyCompanyBundle\Entity\Term\ActionType;

class LoadActionType extends AbstractTermLoad
{
    static $class = ActionType::class;

    public function getTerms()
    {
        return array(
            'Adaptation au poste de travail',
            'Evolution prévisible des métiers',
            'Développpement de nouvelles compétences'
        );
    }
}
