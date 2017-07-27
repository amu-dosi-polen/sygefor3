<?php

namespace Sygefor\Bundle\MyCompanyBundle\Controller;


use Sygefor\Bundle\MyCompanyBundle\Entity\Inscription;
use Sygefor\Bundle\InscriptionBundle\Controller\AbstractInscriptionController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sygefor\Bundle\MyCompanyBundle\Entity\Presence;
use Sygefor\Bundle\MyCompanyBundle\Form\PresenceType;
use Sygefor\Bundle\MyCompanyBundle\Form\InscriptionType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use JMS\SecurityExtraBundle\Annotation\SatisfiesParentSecurityPolicy;

/**
 * @Route("/inscription")
 */
class InscriptionController extends AbstractInscriptionController
{
    protected $inscriptionClass = Inscription::class;

     /**
     * This action attach a form to the return array when the user has the permission to edit the training.
     *
     * @Route("/editpresence/{presence}", name="presence.edit", options={"expose"=true}, defaults={"_format" = "json"})
     * @ParamConverter("presence", class="SygeforMyCompanyBundle:Presence", options={"id" = "presence"})
     * @Rest\View(serializerGroups={"Default", "inscription"}, serializerEnableMaxDepthChecks=true)
     */
    public function editpresenceAction(Presence $presence, Request $request )
    {
        $inscription = $presence->getInscription();
        $form = $this->createForm(new PresenceType(), $presence);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                //Mise à jour presence
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }

        return array('form' => $form->createView(), 'presence' => $presence);

    }
}
