<?php
/**
 * Created by PhpStorm.
 * User: erwan
 * Date: 9/15/16
 * Time: 10:42 AM
 */

namespace Sygefor\Bundle\FrontBundle\Controller;


use Sygefor\Bundle\ApiBundle\Controller\Account\AbstractRegistrationAccountController;
use Sygefor\Bundle\MyCompanyBundle\Entity\Inscription;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This controller regroup actions related to registration.
 *
 * @Route("/account")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class RegistrationAccountController extends AbstractRegistrationAccountController
{
    protected $inscriptionClass = Inscription::class;

    /**
     * Checkout registrations cart.
     *
     * @Route("/checkout", name="front.account.checkout")
     * @Template("@SygeforFront/Account/registration/checkout.html.twig")
     */
    public function checkoutAction(Request $request, $sessions = array())
    {
        if (!$this->getUser()->getIsActive()) {
            throw new ForbiddenOverwriteException("You account is not active");
        }

        $inscription = $this->getDoctrine()->getManager()->getRepository('SygeforMyCompanyBundle:Inscription')->find($request->get('inscriptionId'));
        $this->sendCheckoutNotification(array($inscription), $inscription->getTrainee());

        return $this->redirectToRoute('front.account.registrations');
    }

    /**
     * Registrations.
     *
     * @Route("/registrations", name="front.account.registrations")
     * @Template("@SygeforFront/Account/registration/registrations.html.twig")
     * @Method("GET")
     */
    public function registrationsAction(Request $request)
    {
        $inscriptions = parent::registrationsAction($request);
        $upcoming = array();
        $upcomingIds = array();
        $past = array();
        $now = new \DateTime();
        $sup = "vide";
        foreach ($inscriptions as $inscription) {
            if ($inscription->getSession()->getDateBegin() < $now) {
                $past[] = $inscription;
                $inscription->upcoming = false;
            }
            else {
                $inscription->upcoming = true;
                $upcoming[] = $inscription;
                $upcomingIds[] = $inscription->getId();
                if ($inscription->getInscriptionStatus()->getName() == "En attente") {
                    $sup = $inscription->getTrainee()->getFirstNameSup() ." ". $inscription->getTrainee()->getLastNameSup();
                }
            }
        }

        if ($sup!="vide") {

//            $this->get('session')->getFlashBag()->add('warning', 'Le supérieur hiérarchique que vous avez renseigné est '.$sup.'. Si ce n\'est pas la bonne personne, merci de mettre à jour la donnée dans l\'onglet "Mon profil".');
        }

        return array('user' => $this->getUser(), 'upcoming' => $upcoming, 'past' => $past, 'upcomingIds' => implode(',', $upcomingIds));
    }

    /**
     * Desist a registration.
     *
     * @Route("/registration/{id}/desist", name="front.account.registration.desist")
     * @Template("@SygeforFront/Account/registration/registration-desist.html.twig")
     */
    public function desistAction($id, Request $request)
    {
        $registration = $this->getDoctrine()->getRepository('SygeforInscriptionBundle:AbstractInscription')->find($id);
        $registration->pending = $registration->getInscriptionStatus()->getId() === 1;
        if ($request->getMethod() === "POST") {
            if (parent::desistAction($id, $request)['desisted']) {
                $this->get('session')->getFlashBag()->add('success', 'Votre désistement a bien été enregistré.');
                return $this->redirectToRoute('front.account.registrations');
            }
        }

        return array('user' => $this->getUser(), 'registration' => $registration);
    }

    /**
     * Authorize a registration.
     *
     * @Route("/registration/{id}/authorize", name="front.account.registration.authorize")
     */
    public function authorizeAction($id, Request $request)
    {
        $registration = $this->getDoctrine()->getRepository('SygeforInscriptionBundle:AbstractInscription')->find($id);
        $registration->pending = $registration->getInscriptionStatus()->getId() === 1;

        // Lien vers la page d'autorisation
        $lien = "https://sygefor3.univ-amu.fr/account/registration/".$id."/valid";

        // Envoyer un mail au supérieur hiérarchique
        $templateTerm = $this->container->get('sygefor_core.vocabulary_registry')->getVocabularyById('sygefor_trainee.vocabulary_email_template');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(get_class($templateTerm));
        /** @var EmailTemplate $template */
        $templates = $repo->findBy(array('name' => "Demande de validation d'inscription"));
        $subject = $templates[0]->getSubject();
        $body = $templates[0]->getBody();
        $newbody = str_replace("[session.formation.nom]", $registration->getSession()->getTraining()->getName(), $body);
        $newbody = str_replace("[session.dateDebut]", $registration->getSession()->getDateBegin()->format('d/m/Y'), $newbody);
        $newbody = str_replace("[stagiaire.prenom]", $registration->getTrainee()->getFirstName(), $newbody);
        $newbody = str_replace("[stagiaire.nom]", $registration->getTrainee()->getLastName(), $newbody);
        $newbody = str_replace("[lien]", $lien, $newbody);

        $message = \Swift_Message::newInstance();
        $message->setFrom($this->container->getParameter('mailer_from'), "Sygefor AMU");
        $message->setReplyTo($registration->getSession()->getTraining()->getOrganization()->getEmail());
        $message->setTo($registration->getTrainee()->getEmailSup());
        $message->setSubject($subject);
        $message->setBody($newbody);

        $this->container->get('mailer')->send($message);

        $this->get('session')->getFlashBag()->add('success', 'Votre demande d\'autorisation a bien été envoyée.');
        return $this->redirectToRoute('front.account.registrations');

    }

    /**
     * Valid registration
     * @Route("/registration/{id}/valid", name="front.account.registration.valid")
     * @Template("@SygeforFront/Account/registration/registration-valid.html.twig")
     *
     */
    public function validAction($id, Request $request)
    {
        // Authentification et récup du mail retourné par Shibboleth
        $shibbolethAttributes = $this->get('security.token_storage')->getToken()->getAttributes();
        $supMail = $shibbolethAttributes['mail'];
        $supFirstName = $shibbolethAttributes['givenName'];
        $supLastName = $shibbolethAttributes['sn'];

        // Récupération des infos de l'inscription
        $registration = $this->getDoctrine()->getRepository('SygeforInscriptionBundle:AbstractInscription')->find($id);
        $dateSession = $registration->getSession()->getDateBegin()->format('d/m/Y');
        $nameTraining = $registration->getSession()->getTraining()->getName();

        // Récupération des infos du stagiaire
        $nameTrainee = $registration->getTrainee()->getFullName();
        $supMailTrainee = $registration->getTrainee()->getEmailSup();

        // Création du formulaire d'autorisation
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->add('validation', ChoiceType::class, array(
                'choices' => array('ok' => 'Favorable', 'nok' => 'Défavorable'),
                'expanded' => true,
                'multiple' => false,
                'data' => 'ok',
                'label' => "Avis"
            ))
            ->getForm();
        $form->handleRequest($request);

        // Si la personne authentifiée est bien le supérieur hiérarchique
        if ($supMailTrainee == $supMail) {
            // On vérifie que la demande n'a pas déjà été traitée (statut de l'inscription =1 ou 2)
            if ($registration->getInscriptionStatus()->getId() < 3) {
                // On renvoie vers le formulaire d'autorisation
                $access = "Formulaire";

                if ($form->isSubmitted() && $form->isValid()) {
                    // Récupération de la décision
                    $dataForm = $form->getData();
                    if (isset($dataForm)) {
                        if ($dataForm['validation'] == "ok") {
                            // Si avis favorable, on modifie le statut de l'inscription et on envoie un mail au stagiaire
                            $registration->setInscriptionStatus(
                                $this->getDoctrine()->getRepository('SygeforInscriptionBundle:Term\InscriptionStatus')->findOneBy(
                                    array('machineName' => 'favorable')
                                )
                            );
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($registration);
                            $em->flush();

                            $body = "Bonjour,\n" .
                                "Votre inscription à la session du " . $registration->getSession()->getDateBegin()->format('d/m/Y') . "\nde la formation intitulée '" . $registration->getSession()->getTraining()->getName() . "'\n"
                                . "a été approuvée par " . $supFirstName . " " . $supLastName . "\n";

                            $message = \Swift_Message::newInstance();
                            $message->setFrom($this->container->getParameter('mailer_from'), "Sygefor AMU");
                            $message->setReplyTo($registration->getSession()->getTraining()->getOrganization()->getEmail());
                            $message->setTo($registration->getTrainee()->getEmail());
                            $message->setSubject("Avis favorable pour inscription à une formation");
                            $message->setBody($body);

                            $this->container->get('mailer')->send($message);

                            $this->get('session')->getFlashBag()->add('success', 'L\'avis favorable a bien été émis.');

                        } else {
                            // Sinon, on modifie le statut de l'inscription à "refusé" et on envoie un mail au stagiaire
                            // Si avis défavorable, on modifie le statut de l'inscription et on envoie un mail au stagiaire
                            $registration->setInscriptionStatus(
                                $this->getDoctrine()->getRepository('SygeforInscriptionBundle:Term\InscriptionStatus')->findOneBy(
                                    array('machineName' => 'refuse')
                                )
                            );
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($registration);
                            $em->flush();

                            $body = "Bonjour,\n" .
                                "Votre inscription à la session du " . $registration->getSession()->getDateBegin()->format('d/m/Y') . "\nde la formation intitulée '" . $registration->getSession()->getTraining()->getName() . "'\n"
                                . "a été refusée par " . $supFirstName . " " . $supLastName . "\n";

                            $message = \Swift_Message::newInstance();
                            $message->setFrom($this->container->getParameter('mailer_from'), "Sygefor AMU");
                            $message->setReplyTo($registration->getSession()->getTraining()->getOrganization()->getEmail());
                            $message->setTo($registration->getTrainee()->getEmail());
                            $message->setSubject("Avis défavorable pour inscription à une formation");
                            $message->setBody($body);

                            $this->container->get('mailer')->send($message);

                            $this->get('session')->getFlashBag()->add('success', 'L\'avis défavorable a bien été émis.');

                        }
                    }
                    $access = "Avis émis";
                }
            }
            else {
                $access = "Demande déjà traitée";
            }
        }
        else {
            // Sinon, on affiche un message d'erreur
            $access = "Non autorisé";
        }

        return array('form'=> $form->createView(), 'trainee' => $registration->getTrainee(), 'registration' => $registration, 'access' => $access);
    }

    /**
     * Download a authorization form.
     *
     * @Route("/registration/{ids}/authorization", name="front.account.registration.authorization")
     * @Method("GET")
     */
    public function authorizationAction($ids, Request $request)
    {
        return parent::authorizationAction($ids, $request);
    }
}