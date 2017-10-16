<?php

namespace Sygefor\Bundle\MyCompanyBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;
use Sygefor\Bundle\MyCompanyBundle\Form\TraineeType;
use Sygefor\Bundle\TraineeBundle\Entity\AbstractTrainee;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sygefor\Bundle\TraineeBundle\Entity\DisciplinaryTrait;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @ORM\Column(name="birth_date", type="datetime")
     * @Assert\NotBlank(message="Vous devez préciser une date de naissance.")
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $birthDate;

    /**
     * @ORM\Column(name="amu_statut", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $amuStatut;

    /**
     * @ORM\Column(name="corps", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $corps;

    /**
     * @ORM\Column(name="category", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $category;

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
     * @ORM\Column(name="fonction", type="string", length=255)
     * @Serializer\Groups({"Default", "trainee", "api"})
     */
    protected $fonction;

    /**
     * @return mixed
     */
    static public function getFormType()
    {
        return TraineeType::class;
    }

    /**
     * Set birth date
     *
     * @param mixed $birthDate
     *
     * @return Trainee
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birth date
     *
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set amuStatut
     *
     * @param mixed $amuStatut
     *
     * @return Trainee
     */
    public function setAmuStatut($amuStatut)
    {
        $this->amuStatut = $amuStatut;

        return $this;
    }

    /**
     * Get amuStatut
     *
     */
    public function getAmuStatut()
    {
        return $this->amuStatut;
    }

    /**
     * Set corps
     *
     * @param mixed $corps
     *
     * @return Trainee
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set category
     *
     * @param mixed $category
     *
     * @return Trainee
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     */
    public function getCategory()
    {
        return $this->category;
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
     * Set fonction
     *
     * @param string $fonction
     *
     * @return Trainee
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
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
