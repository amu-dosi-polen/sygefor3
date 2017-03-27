<?php
/**
 * Created by PhpStorm.
 * User: erwan
 * Date: 9/15/16
 * Time: 10:43 AM
 */

namespace Sygefor\Bundle\FrontBundle\Controller;


use Monolog\Logger;
use Sygefor\Bundle\ApiBundle\Controller\Account\AbstractAnonymousAccountController;
use Sygefor\Bundle\FrontBundle\Form\ProfileType;
use Sygefor\Bundle\MyCompanyBundle\Entity\Trainee;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This controller regroup all public actions relative to account.
 *
 * @Route("/account")
 */
class AnonymousAccountController extends AbstractAnonymousAccountController
{
    protected $traineeClass = Trainee::class;

    /**
     * Register a new account with data.
     *
     * @Route("/register", name="front.account.register")
     * @Template("@SygeforFront/Account/profile/account-registration.html.twig")
     */
    public function registerAction(Request $request)
    {
        $trainee = new Trainee();
        $shibbolethAttributes = $this->get('security.token_storage')->getToken()->getAttributes();
        $trainee->setTitle($this->getDoctrine()->getRepository('SygeforCoreBundle:PersonTrait\Term\Title')->findOneBy(
            array('name' => $shibbolethAttributes['supannCivilite'])
        ));
        $trainee->setOrganization($this->getDoctrine()->getRepository('SygeforCoreBundle:Organization')->find(1));

        $trainee->setLastName($shibbolethAttributes['sn']);
        $trainee->setFirstName($shibbolethAttributes['givenName']);
        $trainee->setEmail($shibbolethAttributes['mail']);
        $trainee->setAddress($shibbolethAttributes['street']);
        $trainee->setZip($shibbolethAttributes['postalCode']);
        $trainee->setCity($shibbolethAttributes['postalAddress']);
        $trainee->setPhoneNumber($shibbolethAttributes['telephoneNumber']);
        $trainee->setPublicType($this->getDoctrine()->getRepository('Sygefor\Bundle\TraineeBundle\Entity\Term\PublicType')->findOneBy(
            array('name' => $shibbolethAttributes['primary_affiliation'])
        ));
        $trainee->setStatus($shibbolethAttributes['postalCode']);
        $trainee->setService($shibbolethAttributes['supannEntiteAffectation']);
        
        // Etablissement
        $eppn = $shibbolethAttributes['eppn'];
        if (stripos($eppn , "@")>0) {
            $domaine = substr($eppn, stripos($eppn, "@") + 1);
            switch($domaine) {
                case "univ-amu.fr":
                    $trainee->setInstitution($this->getDoctrine()->getRepository('Sygefor\Bundle\InstitutionBundle\Entity\AbstractInstitution')->findOneBy(
                        array('name' => "AMU")
                    ));
                    break;
                default:
                    $trainee->setInstitution($this->getDoctrine()->getRepository('Sygefor\Bundle\InstitutionBundle\Entity\AbstractInstitution')->findOneBy(
                        array('name' => "AMU")
                    ));
                    break;
            }
        }

        $form = $this->createForm(new ProfileType($this->get('sygefor_core.access_right_registry')), $trainee);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                parent::registerShibbolethTrainee($request, $trainee, true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($trainee);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Votre profil a bien été créé.');
                $this->get('security.token_storage')->getToken()->setUser($trainee);

                return $this->redirectToRoute('front.account');
            }
        }

        return array('user' => $this->getUser(), 'form' => $form->createView());
    }
}
