<?php
/**
 * Created by PhpStorm.
 * User: erwan
 * Date: 9/15/16
 * Time: 11:00 AM
 */

namespace Sygefor\Bundle\FrontBundle\Controller;


use Doctrine\ORM\EntityManager;
use Sygefor\Bundle\ApiBundle\Controller\TrainingController;
use Sygefor\Bundle\MyCompanyBundle\Entity\Inscription;
use Sygefor\Bundle\FrontBundle\Form\InscriptionType;
use Sygefor\Bundle\TraineeBundle\Entity\AbstractTrainee;
use Sygefor\Bundle\TraineeBundle\Entity\Term\EmailTemplate;
use Sygefor\Bundle\MyCompanyBundle\Entity\Session;
use Sygefor\Bundle\MyCompanyBundle\Entity\Internship;
use Sygefor\Bundle\TrainingBundle\Entity\Training\AbstractTraining;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;
use Symfony\Component\HttpFoundation\Request;
Use Elastica\Query;
Use Elastica\Filter\BoolAnd;
Use Elastica\Filter\Term;
Use Elastica\Filter\Range;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/")
 */
class PublicController extends Controller
{
    protected $apiTrainingController;

    public function __construct()
    {
        $this->apiTrainingController = new TrainingController();
    }

    /**
     * @Route("/{page}", name="front.public.index", requirements={"page": "\d+"})
     * @Template
     */
    public function indexAction(Request $request, $page = 1)
    {
        $this->apiTrainingController->setContainer($this->container);
        $search = $this->createProgramQuery($page);

        if ($request->get('shibboleth') == 1) {
            if ($request->get('error') == "activation") {
                $this->get('session')->getFlashBag()->add('warning', "Votre compte doit être activé par un administrateur avant de pouvoir vous connecter.");
            }
        }

        return array('user' => $this->getUser(), 'search' => $search, 'page' => $page);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Sygefor\Bundle\TrainingBundle\Entity\Training\AbstractTraining $training
     * @param null $sessionId
     * @param null $token
     *
     * @Route("/training/{id}/{sessionId}/{token}", name="front.public.training", requirements={"id": "\d+", "sessionId": "\d+"})
     * @ParamConverter("training", class="SygeforTrainingBundle:Training\AbstractTraining", options={"id" = "id"})
     * @Template("@SygeforFront/Public/program/training.html.twig")
     *
     * @return array
     */
    public function trainingAction(Request $request, AbstractTraining $training, $sessionId = null, $token = null)
    {
        $this->apiTrainingController->setContainer($this->container);
        $training = $this->apiTrainingController->trainingAction($training);
        $focusSession = null;
        foreach ($training->getSessions() as $session) {
            if ($session->getId() == $sessionId) {
                $focusSession = $session;
                break;
            }
        }

        $now = new \DateTime();
        $pastSessions = array();
        $upcomingSessions = array();
        /** @var Session $session */
        foreach ($training->getSessions() as $session) {

            $inscription = null;
            if ($this->getUser() instanceof AbstractTrainee) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $inscription = $em->getRepository('SygeforInscriptionBundle:AbstractInscription')->createQueryBuilder('inscription')
                ->leftJoin('SygeforTrainingBundle:Session\AbstractSession', 'session', 'WITH', 'inscription.session = session.id')
                ->leftJoin('SygeforTraineeBundle:AbstractTrainee', 'trainee', 'WITH', 'inscription.trainee = trainee.id')
                ->where('session.id = :sessionId')
                ->andWhere('trainee.id = :traineeId')
                ->setParameter('sessionId', $sessionId)
                ->setParameter('traineeId', $this->getUser()->getId())->getQuery()->execute();
            }

            $session->isRegistered = !empty($inscription);
            $session->getDateBegin() > $now ? $upcomingSessions[] = $session : $pastSessions[] = $session;
            if ($session->getRegistration() === $session::REGISTRATION_PRIVATE && (!method_exists($session, 'getModule') || !$session->getModule())) {
                $session->availablePrivateSession = true;
            }
            else {
                $session->availablePrivateSession = false;
            }
            if (method_exists($session, 'getModule') && $session->getModule()) {
                $session->moduleToken = md5($session->getTraining()->getType() . $session->getTraining()->getId()) === $token;
            }

        }

        if ($this->getUser() && !$this->getUser()->getIsActive()) {
            $this->get('session')->getFlashBag()->add('warning', "Vous ne pouvez pas vous inscrire à une session tant que votre compte n'a pas
             été validé par un administrateur.");
        }

        return array(
            'user' => $this->getUser(),
            'training' => $training,
            'session' => $focusSession,
            'upcomingSessions' => $upcomingSessions,
            'pastSessions' => $pastSessions,
            'token' => $token
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Sygefor\Bundle\TrainingBundle\Entity\Training\AbstractTraining $training
     * @param \Sygefor\Bundle\MyCompanyBundle\Entity\Session $session
     * @param null $token
     *
     * @Route("/training/inscription/{id}/{sessionId}/{token}", name="front.public.inscription", requirements={"id": "\d+", "sessionId": "\d+"})
     * @ParamConverter("training", class="SygeforTrainingBundle:Training\AbstractTraining", options={"id" = "id"})
     * @ParamConverter("session", class="SygeforMyCompanyBundle:Session", options={"id" = "sessionId"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Template("@SygeforFront/Public/program/inscription.html.twig")
     *
     * @return array
     */
    public function inscriptionAction(Request $request, AbstractTraining $training, Session $session, $token = null)
    {
        // in case shibboleth authentication done but user has not registered his account
        if (!is_object($this->getUser())) {
            return $this->redirectToRoute('front.account.register');
        }

        if (!$this->getUser()->getIsActive()) {
            $this->get('session')->getFlashBag()->add('warning', "Vous ne pouvez pas vous inscrire à une session tant que votre compte n'a pas
             été validé par un administrateur.");
            return $this->redirectToRoute('front.public.training', array('id' => $training->getId(), 'sessionId' => $session->getId(), 'token' => $token));
        }

        $this->apiTrainingController->setContainer($this->container);
        $training = $this->apiTrainingController->trainingAction($training);
        if (method_exists($session, 'getModule') && $session->getModule()) {
            $session->moduleToken = md5($session->getTraining()->getType() . $session->getTraining()->getId()) === $token;
        }

        $inscription = $this->getDoctrine()->getManager()->getRepository('SygeforInscriptionBundle:AbstractInscription')->findOneBy(array(
            'trainee' => $this->getUser(),
            'session'=> $session
        ));
        if ($inscription) {
            $this->get('session')->getFlashBag()->add('warning', "Vous êtes déjà inscrit à cette session.");
            return $this->redirectToRoute('front.account.registrations');
            //throw new ForbiddenOverwriteException('An inscription has already been found');
        }
        if (!$inscription) {
            $inscription = new Inscription();
            $inscription->setTrainee($this->getUser());
            $inscription->setSession($session);
        }
        $inscription->setInscriptionStatus(
            $this->getDoctrine()->getRepository('SygeforInscriptionBundle:Term\InscriptionStatus')->findOneBy(
                array('machineName' => 'waiting')
            )
        );

        $publicType = $this->getUser()->getPublicType();
        $publicRestrict = $training->getPublicTypesRestrict();
        $flagInsc = false;
        if (sizeof($publicRestrict)) {
            foreach ($publicRestrict as $public) {
                if ($publicType == $public) {
                    $flagInsc = true;
                }
            }
        } else {
            $flagInsc = true;
        }


        if ($flagInsc) {
            $form = $this->createForm(new InscriptionType(), $inscription);
            if ($request->getMethod() === 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inscription);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Votre inscription a bien été enregistrée.');

                    $id = $inscription->getId();
                    // Lien vers la page d'autorisation
                    $lien = "https://sygefor3.univ-amu.fr/account/registration/" . $id . "/valid";


                    if ($form['authorization']->getData() == TRUE) {
                        $templateTerm = $this->container->get('sygefor_core.vocabulary_registry')->getVocabularyById('sygefor_trainee.vocabulary_email_template');
                        $repo = $em->getRepository(get_class($templateTerm));
                        /** @var EmailTemplate $template */
                        $templates = $repo->findBy(array('name' => "Demande de validation d'inscription"));
                        $subject = $templates[0]->getSubject();
                        $body = $templates[0]->getBody();
                        $newbody = str_replace("[session.formation.nom]", $inscription->getSession()->getTraining()->getName(), $body);
                        $newbody = str_replace("[session.dateDebut]", $inscription->getSession()->getDateBegin()->format('d/m/Y'), $newbody);
                        $newbody = str_replace("[stagiaire.prenom]", $inscription->getTrainee()->getFirstName(), $newbody);
                        $newbody = str_replace("[stagiaire.nom]", $inscription->getTrainee()->getLastName(), $newbody);
                        $newbody = str_replace("[lien]", $lien, $newbody);

                        // Envoyer un mail au supérieur hiérarchique
                        /*$body = "Bonjour,\n" .
                            "Une inscription à la session du " . $inscription->getSession()->getDateBegin()->format('d/m/Y') . "\nde la formation intitulée '" . $inscription->getSession()->getTraining()->getName() . "'\n"
                            . "a été réalisée par ".$inscription->getTrainee()->getFullName() .".\n"
                            . "Pour autoriser ". $inscription->getTrainee()->getFullName()  . " à participer à cette formation, merci de valider l'inscription en cliquant sur le lien suivant :". "\n"
                            . "http://www.univ-amu.fr";
                        */
                        $message = \Swift_Message::newInstance();
                        $message->setFrom($this->container->getParameter('mailer_from'), "Sygefor AMU");
                        $message->setReplyTo($inscription->getSession()->getTraining()->getOrganization()->getEmail());
                        $message->setTo($inscription->getTrainee()->getEmailSup());
                        $message->setSubject($subject);
                        $message->setBody($newbody);

                        $this->container->get('mailer')->send($message);

                    }


                    return $this->redirectToRoute(
                        'front.account.checkout', array(
                            'inscriptionId' => $inscription->getId())
                    );
                }
            }

            $sup = $inscription->getTrainee()->getFirstNameSup() . " " . $inscription->getTrainee()->getLastNameSup();
            $this->get('session')->getFlashBag()->add('warning', 'Le supérieur hiérarchique que vous avez renseigné est ' . $sup . '. Si ce n\'est pas la bonne personne, merci de mettre à jour la donnée dans le menu "Mon compte", onglet "Mon profil".');

            return array(
                'user' => $this->getUser(),
                'form' => $form->createView(),
                'training' => $training,
                'session' => $session,
                'token' => $token,
                'flag' => $flagInsc
            );
        }
        else {
            //$this->get('session')->getFlashBag()->add('error', "Vous ne pouvez pas vous inscrire à cette session car vous ne faites pas partie des publics cibles autorisés à s'inscrire.");
            return array(
                'user' => $this->getUser(),
                'training' => $training,
                'session' => $session,
                'flag' => $flagInsc
            );
        }
    }

    /**
     * @Route("/login", name="front.public.login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        return array('user' => $this->getUser());
    }

    /**
     * @Route("/contact", name="front.public.contact")
     * @Template()
     */
    public function contactAction(Request $request)
    {
        return array('user' => $this->getUser());
    }

    /**
     * @Route("/faq", name="front.public.faq")
     * @Template()
     */
    public function faqAction(Request $request)
    {
        return array('user' => $this->getUser());
    }

    /**
     * @Route("/about", name="front.public.about")
     * @Template()
     */
    public function aboutAction(Request $request)
    {
        return array('user' => $this->getUser());
    }

    /**
     * @Route("/legalNotice", name="front.public.legalNotice")
     * @Template()
     */
    public function legalNoticeAction(Request $request)
    {
        return array('user' => $this->getUser());
    }


    /**
     * @param $page
     * @param int $itemPerPage
     * @param $code
     * @return array
     */
    protected function createProgramQuery($page, $itemPerPage = 10, $code = null)
    {
        $search = $this->get('sygefor_training.session.search');
        if ($page) {
            $search->setPage($page);
            $search->setSize($itemPerPage);
        }

        // add filters
        $filters = new BoolAnd();
        if (!empty($code)) {
            $organization = new Term(array('training.organization.code' => $code));
            $filters->addFilter($organization);
        }

        $dateBegin = new Range('dateBegin', array("gte" => (new \DateTime("now", timezone_open('Europe/Paris')))->format('Y-m-d')));
        $filters->addFilter($dateBegin);

//        $types = new Terms('training.type', array('internship'));
//        $filters->addFilter($types);

        $search->addFilter('filters', $filters);
        $search->addSort('training.theme.source');
        $search->addSort('dateBegin');
        $search->addSort('training.name.source');

        return $search->search();
    }
}