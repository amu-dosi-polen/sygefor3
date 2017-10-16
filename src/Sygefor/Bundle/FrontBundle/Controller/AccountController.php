<?php
/**
 * Created by PhpStorm.
 * User: erwan
 * Date: 9/15/16
 * Time: 11:00 AM
 */

namespace Sygefor\Bundle\FrontBundle\Controller;


use Sygefor\Bundle\FrontBundle\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sygefor\Bundle\MyCompanyBundle\Entity\Trainee;
use Sygefor\Bundle\MyCompanyBundle\Entity\SupannCodeEntite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/account")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class AccountController extends Controller
{
    /**
     * @Route("/", name="front.account")
     *
     * @return RedirectResponse
     */
    public function accountAction(Request $request)
    {
        // Récupération des attributs Shibboleth pour mise à jour du profil
        $shibbolethAttributes = $this->get('security.token_storage')->getToken()->getAttributes();
        $trainee = new Trainee();

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
        $services = explode(";", $shibbolethAttributes['supannEntiteAffectation']);
        $servicelib = "";
        foreach($services as $service) {
            $supannCodeEntite = $this->getDoctrine()->getRepository('SygeforMyCompanyBundle:SupannCodeEntite')->findOneBy(
                array('supannCodeEntite' => $service)
            );
            if ($supannCodeEntite!=null) {
                $servicelib .= $supannCodeEntite->getDescription() . " ; ";
            }
        }
        $trainee->setService($servicelib);
        $trainee->setAmuStatut($shibbolethAttributes['amuStatut']);
        $corps = ltrim($shibbolethAttributes['supannEmpCorps'], "{NCORPS}");
        $n_corps = $this->getDoctrine()->getRepository('SygeforMyCompanyBundle:Corps')->findOneBy(
            array('corps' => $corps)
        );
        $trainee->setCorps($n_corps->getLibelleLong());
        $trainee->setCategory($n_corps->getCategory());

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

        $user = $this->getUser();
        if ($user) {
            if ($user->getIsActive()) {
                // Mise à jour du profil en base de données
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                // redirect user to registrations pages
                $url = $this->generateUrl('front.account.registrations');
            }
            else {
                return $this->redirectToRoute('front.account.logout', array('return' => $this->generateUrl('front.public.index', array('shibboleth' => 1, 'error' => 'activation'))));
            }
        }
        else {
            // redirect user to registration form
            $url = $this->generateUrl('front.account.register');
        }

        return new RedirectResponse($url);
    }

    /**
     * @param Request $request
     *
     * @Route("/profile", name="front.account.profile")
     * @Template("@SygeforFront/Account/profile/profile.html.twig")
     *
     * @return array
     */
    public function profileAction(Request $request)
    {
        $options = array();
        // Mise à jour du profil avec les attributs récupérés par Shibboleth
        $shibbolethAttributes = $this->get('security.token_storage')->getToken()->getAttributes();
        $trainee = $this->getUser();
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

        // Mise en forme pour le service
        $services = explode(";", $shibbolethAttributes['supannEntiteAffectation']);
        $servicelib = "";
        foreach($services as $service) {
            $supannCodeEntite = $this->getDoctrine()->getRepository('SygeforMyCompanyBundle:SupannCodeEntite')->findOneBy(
                array('supannCodeEntite' => $service)
            );
            if ($supannCodeEntite!=null) {
                $servicelib .= $supannCodeEntite->getDescription() . " ; ";
            }
        }
        $trainee->setService($servicelib);


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
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Votre profil a été mis à jour.');
            }
        }

        return array('user' => $this->getUser(), 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @param string $return
     *
     * @Route("/logout/{return}", name="front.account.logout", requirements={"return" = ".+"})
     *
     * @return array
     */
    public function logoutAction(Request $request, $return = null)
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->get('shibboleth')->getLogoutUrl($request, $return ? $return : $this->generateUrl('front.public.index')));
    }
}