<?php

namespace Sygefor\Bundle\MyCompanyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sygefor\Bundle\MyCompanyBundle\Entity\Participation;
use Sygefor\Bundle\MyCompanyBundle\Entity\Session;
use Sygefor\Bundle\MyCompanyBundle\Entity\DateSession;
use Sygefor\Bundle\MyCompanyBundle\Form\DateSessionType;
use Sygefor\Bundle\TrainingBundle\Controller\AbstractSessionController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/training/session")
 */
class SessionController extends AbstractSessionController
{
    protected $sessionClass = Session::class;
    protected $participationClass = Participation::class;
    protected $dateClass = DateSession::class;

    /**
     * @Route("/adddates/{session}", name="dates.add", requirements={"id" = "\d+"}, options={"expose"=true}, defaults={"_format" = "json"})
     * @SecureParam(name="session", permissions="EDIT")
     * @ParamConverter("session", class="SygeforMyCompanyBundle:Session", options={"id" = "session"})
     * @Rest\View(serializerGroups={"Default", "session"}, serializerEnableMaxDepthChecks=true)
     */
    public function adddatesAction(Session $session, Request $request)
    {
        $dateSession = new $this->dateClass;
        $dateSession->setSession($session);

        $form        = $this->createForm(new DateSessionType(), $dateSession);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $existingDate = null;
                /** @var DateSession $existingDate */
                foreach ($session->getDates() as $existingDate) {
                    if ($existingDate->getDateBegin() === $dateSession->getDateBegin()) {
                        $form->get('dates')->addError(new FormError('Cette date est déjà associé à cet évènement.'));
                        break;
                    }
                }

                if (!$existingDate || ($existingDate->getDateBegin() !== $dateSession->getDateBegin())) {
                    $session->addDates($dateSession);
                    $session->updateTimestamps();
                    $session->getTraining()->updateTimestamps();
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($dateSession);
                    $em->flush();
                }
             }
        }

        return array('form' => $form->createView(), 'date' => $dateSession);
    }

        /**
     * @Route("/{session}/remove/{dates}", name="dates.remove", options={"expose"=true}, defaults={"_format" = "json"})
     * @Method("POST")
     * @SecureParam(name="session", permissions="EDIT")
     * @ParamConverter("session", class="SygeforMyCompanyBundle:Session", options={"id" = "session"})
     * @ParamConverter("dates", class="SygeforMyCompanyBundle:DateSession", options={"id" = "dates"})
     * @Rest\View(serializerGroups={"Default", "session"}, serializerEnableMaxDepthChecks=true)
     */
    public function removedatesAction(Session $session, DateSession $dates)
    {
        $session->removeDate($dates);
        $session->updateTimestamps();
        $session->getTraining()->updateTimestamps();
        $this->getDoctrine()->getManager()->remove($dates);
        $this->getDoctrine()->getManager()->flush();

        return;
    }

    /**
     * This action attach a form to the return array when the user has the permission to edit the training.
     *
     * @Route("/editdates/{dates}", name="dates.edit", options={"expose"=true}, defaults={"_format" = "json"})
     * @ParamConverter("dates", class="SygeforMyCompanyBundle:DateSession", options={"id" = "dates"})
     * @Rest\View(serializerGroups={"Default", "session"}, serializerEnableMaxDepthChecks=true)
     */
    public function editdatesAction(DateSession $dates, Request $request )
    {
        $form = $this->createForm(new DateSessionType(), $dates);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }

        return array('form' => $form->createView(), 'dates' => $dates);
    }

    /**
     * This action attach a form to the return array when the user has the permission to edit the training.
     *
     * @Route("/viewdates/{dates}", name="dates.view", options={"expose"=true}, defaults={"_format" = "json"})
     * @ParamConverter("dates", class="SygeforMyCompanyBundle:DateSession", options={"id" = "dates"})
     * @Rest\View(serializerGroups={"Default", "session"}, serializerEnableMaxDepthChecks=true)
     */
    public function viewdatesAction(DateSession $dates, Request $request )
    {
        $form = $this->createForm(new DateSessionType(), $dates);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }

        return array('form' => $form->createView(), 'dates' => $dates);
    }
}
