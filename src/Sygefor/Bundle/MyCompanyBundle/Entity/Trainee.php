<?php

namespace Sygefor\Bundle\MyCompanyBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;
use Sygefor\Bundle\MyCompanyBundle\Form\TraineeType;
use Sygefor\Bundle\TraineeBundle\Entity\AbstractTrainee;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sygefor\Bundle\TraineeBundle\Entity\DisciplinaryTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 * @ORM\Table(name="trainee")
 * @ORM\Entity
 * @UniqueEntity(fields={"email", "organization"}, message="Cette adresse email est déjà utilisée.", ignoreNull=true, groups={"Default", "trainee"})
 */
class Trainee extends AbstractTrainee implements UserInterface
{
    use DisciplinaryTrait;

    /**
     * @ORM\Column(name="first_name_sup", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $firstNameSup;

    /**
     * @ORM\Column(name="last_name_sup", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $lastNameSup;

    /**
     * @ORM\Column(name="email_sup", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $emailSup;

    /**
     * @ORM\Column(name="first_name_corr", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $firstNameCorr;

    /**
     * @ORM\Column(name="last_name_corr", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $lastNameCorr;

    /**
     * @ORM\Column(name="email_corr", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $emailCorr;


    /**
     * @return mixed
     */
    static public function getFormType()
    {
        return TraineeType::class;
    }

    /**
     * Set firstnameSup
     *
     * @param string $firstNameSup
     *
     * @return Trainee
     */
    public function setFirstNameSup($firstNameSup)
    {
        $this->firstNameSup = $firstNameSup;

        return $this;
    }

    /**
     * Get firstnameSup
     *
     * @return string
     */
    public function getFirstNameSup()
    {
        return $this->firstNameSup;
    }

    /**
     * Set lastnameSup
     *
     * @param string $lastNameSup
     *
     * @return Trainee
     */
    public function setLastNameSup($lastNameSup)
    {
        $this->lastNameSup = $lastNameSup;

        return $this;
    }

    /**
     * Get lastnameSup
     *
     * @return string
     */
    public function getLastNameSup()
    {
        return $this->lastNameSup;
    }

    /**
     * Set emailSup
     *
     * @param string $emailSup
     *
     * @return Trainee
     */
    public function setEmailSup($emailSup)
    {
        $this->emailSup = $emailSup;

        return $this;
    }

    /**
     * Get emailSup
     *
     * @return string
     */
    public function getEmailSup()
    {
        return $this->emailSup;
    }

    /**
     * Set firstnameCorr
     *
     * @param string $firstNameCorr
     *
     * @return Trainee
     */
    public function setFirstNameCorr($firstNameCorr)
    {
        $this->firstNameCorr = $firstNameCorr;

        return $this;
    }

    /**
     * Get firstnameCorr
     *
     * @return string
     */
    public function getFirstNameCorr()
    {
        return $this->firstNameCorr;
    }

    /**
     * Set lastnameCorr
     *
     * @param string $lastNameCorr
     *
     * @return Trainee
     */
    public function setLastNameCorr($lastNameCorr)
    {
        $this->lastNameCorr = $lastNameCorr;

        return $this;
    }

    /**
     * Get lastnameCorr
     *
     * @return string
     */
    public function getLastNameCorr()
    {
        return $this->lastNameCorr;
    }

    /**
     * Set emailCorr
     *
     * @param string $emailCorr
     *
     * @return Trainee
     */
    public function setEmailCorr($emailCorr)
    {
        $this->emailCorr = $emailCorr;

        return $this;
    }

    /**
     * Get emailCorr
     *
     * @return string
     */
    public function getEmailCorr()
    {
        return $this->emailCorr;
    }

    /**
     * Add inscription
     *
     * @param \Sygefor\Bundle\InscriptionBundle\Entity\AbstractInscription $inscription
     *
     * @return Trainee
     */
    public function addInscription(\Sygefor\Bundle\InscriptionBundle\Entity\AbstractInscription $inscription)
    {
        $this->inscriptions[] = $inscription;

        return $this;
    }

    /**
     * Remove inscription
     *
     * @param \Sygefor\Bundle\InscriptionBundle\Entity\AbstractInscription $inscription
     */
    public function removeInscription(\Sygefor\Bundle\InscriptionBundle\Entity\AbstractInscription $inscription)
    {
        $this->inscriptions->removeElement($inscription);
    }
}
