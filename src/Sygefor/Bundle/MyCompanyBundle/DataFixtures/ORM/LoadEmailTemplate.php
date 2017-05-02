<?php

/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 02/06/14
 * Time: 14:39.
 */
namespace Sygefor\Bundle\MyCompanyBundle\DataFixtures\ORM;

use Sygefor\Bundle\CoreBundle\DataFixtures\AbstractTermLoad;
use Sygefor\Bundle\TraineeBundle\Entity\Term\EmailTemplate;

/**
 * Class LoadPresenceStatus.
 */
class LoadEmailTemplate extends AbstractTermLoad
{
    static $class = EmailTemplate::class;

    public function getTerms()
    {
        return array(
            array(
                'name'    => "Statut d'inscription : attente de validation",
                'subject' => "Votre demande d'inscription à été prise en compte",
                'body'    => "[stagiaire.civilite],

Nous vous informons que votre demande d'inscription à l'événement \"[session.formation.nom]\", prévu le [session.dateDebut], a été prise en compte et sera traitée dans les plus brefs délais.

Vous pouvez suivre l'évolution de votre demande à partir de votre espace personnel sur notre site : http://front_url.dev/#/account

Avec nos cordiales salutations,
L'équipe Sygefor.",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 1),
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 1),
            ),
            array(
                'name'    => "Statut d'inscription : liste d'attente",
                'subject' => "Votre demande d'inscription à été mise en attente",
                'body'    => "[stagiaire.civilite],

Nous vous informons que votre demande d'inscription au stage \"[session.formation.nom]\", prévu le [session.dateDebut], a été mise en attente, compte tenu du nombre d'inscriptions.
En cas de désistement d'une personne, nous vous inscrirons et vous serez immédiatement prévenu(e).

Les places étant limitées, nous vous prions instamment de nous informer au plus vite en cas de renoncement de votre part, afin de pouvoir proposer votre place à une autre personne.

Avec nos cordiales salutations,
L'équipe Sygefor.",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 2),
                'presenceStatus'    => null,
            ),
            array(
                'name'    => "Statut d'inscription : refus",
                'subject' => "Votre demande d'inscription à été refusée",
                'body'    => "[stagiaire.civilite],

Nous sommes au regret de vous informer que votre demande d'inscription au stage \"[session.formation.nom]\", prévu le [session.dateDebut], n'a pu être acceptée, ni mise en attente, compte tenu du nombre élevé des inscriptions.

Nous espérons que votre demande pourra être satisfaite lors du prochain programme de formations de votre Compagnie.

Avec nos cordiales salutations,
L'équipe Sygefor.",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 3),
                'presenceStatus'    => null,
            ),
            array(
                'name'    => "Statut d'inscription : accepté",
                'subject' => "Votre demande d'inscription à été acceptée",
                'body'    => "[stagiaire.civilite],

Nous avons le plaisir de vous informer que votre demande d'inscription au stage \"[session.formation.nom]\", prévu le [session.dateDebut], a été acceptée.
Vous recevrez, par courrier électronique, une convocation environ deux semaines avant le stage.

Les places étant limitées, nous vous prions instamment de nous informer au plus vite en cas d'indisponibilité de votre part, afin de pouvoir proposer votre place à une autre personne.

Nous rappellons qu'une absence non signalée a un stage entraîne l'annulation des inscriptions à tous les autres stages du programme en cours.

Avec nos cordiales salutations,
L'équipe Sygefor.",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 4),
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 1),
            ),
            array(
                'name'    => "Statut d'inscription : avis favorable du N+1",
                'subject' => "Votre demande d'inscription à reçu un avis favorable de votre supérieur",
                'body'    => "[stagiaire.civilite],

Nous avons le plaisir de vous informer que votre demande d'inscription au stage \"[session.formation.nom]\", prévu le [session.dateDebut], a reçu un avis favorable de la part de votre supérieur hiérarchique.
Avec nos cordiales salutations,
L'équipe Sygefor.",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 6),
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 1),
            ),
            array(
                'name'    => "Statut d'inscription : avis défavorable du N+1",
                'subject' => "Votre demande d'inscription à reçu un avis défavorable de votre supérieur",
                'body'    => "[stagiaire.civilite],

Nous sommes au regret de vous informer que votre demande d'inscription au stage \"[session.formation.nom]\", prévu le [session.dateDebut], a reçu un avis défavorable de la part de votre supérieur hiérarchique.
Avec nos cordiales salutations,
L'équipe Sygefor.",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 3),
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 1),
            ),
            array(
                'name'    => "Statut d'inscription : convoqué",
                'subject' => "Convocation à la formation [session.formation.nom]",
                'body'    => "[stagiaire.civilite],

CE COURRIEL TIENT LIEU DE CONVOCATION.

Nous vous confirmons votre inscription à la formation :

\"[session.formation.nom]\".

Celle-ci se déroulera selon le calendrier ci-dessous :
[session.dates]

Observations/remarques :
[session.commentaires]


Vous vous êtes engagé(e) à suivre cette formation dans son intégralité et nous attirons votre attention sur l'importance de cet engagement.
En cas d'empêchement justifié, nous vous demandons de nous le signaler sans délai et par retour de courriel, afin de proposer la place à un agent inscrit sur liste d'attente.
Veuillez également confirmer votre participation à votre chef de service.
Cordialement,
DRH-Formation",
                'inscriptionStatus' => $this->manager->find('SygeforInscriptionBundle:Term\InscriptionStatus', 7),
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 1),
            ),
            array(
                'name'    => "Demande de validation d'inscription",
                'subject' => "Demande d'autorisation pour inscription à une formation",
                'body'    => "Bonjour,

Une inscription à la formation intitulée \"[session.formation.nom]\", prévue le [session.dateDebut],
a été réalisée par [stagiaire.prenom] [stagiaire.nom].
Pour autoriser la participation à cette formation, merci de valider l'inscription en cliquant sur le lien suivant :
[lien]
Cordialement,
DRH-Formation",
                'inscriptionStatus' => null,
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 1),
            ),
            array(
                'name'    => "Statut d'inscription : désistement",
                'subject' => "Désistement à la formation [session.formation.nom]",
                'body'    => "[stagiaire.civilite],

Nous vous informons que nous avons bien pris en compte votre demande de désistement au stage

\"[session.formation.nom]\",

prévu aux dates suivantes :
[session.dates]


Cordialement,
DRH-Formation",
                'inscriptionStatus' => null,
                'presenceStatus'    => null,
                'organization'      => $this->manager->find('SygeforCoreBundle:Organization', 5),
            ),
        );
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    function getOrder()
    {
        return 2;
    }
}
