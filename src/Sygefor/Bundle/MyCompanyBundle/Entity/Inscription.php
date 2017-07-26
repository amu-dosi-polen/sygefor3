<?php

namespace Sygefor\Bundle\MyCompanyBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Sygefor\Bundle\InscriptionBundle\Entity\AbstractInscription;
use Doctrine\ORM\Mapping as ORM;
use Sygefor\Bundle\MyCompanyBundle\Form\InscriptionType;
use JMS\Serializer\Annotation as Serializer;
use Sygefor\Bundle\TraineeBundle\Entity\DisciplinaryTrait;

/**
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity
 */
class Inscription extends AbstractInscription
{
    use DisciplinaryTrait;

    /**
     * @var String
     * @ORM\Column(name="motivation", type="text", nullable=true)
     * @Serializer\Groups({"Default", "api"})
     */
    protected $motivation;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Sygefor\Bundle\MyCompanyBundle\Entity\EvaluationNotedCriterion", mappedBy="inscription", cascade={"persist", "merge", "remove"})
     * @Serializer\Groups({"training", "inscription", "api.attendance", "session"})
     */
    protected $criteria;

    /**
     * @ORM\Column(name="message", type="text", nullable=true)
     * @Serializer\Groups({"Default", "inscription", "api.attendance"})
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Sygefor\Bundle\MyCompanyBundle\Entity\Term\ActionType")
     * @ORM\JoinColumn(nullable=true)
     * @Serializer\Groups({"Default", "api"})
     */
    protected $actiontype;

    /**
     * @var String
     * @ORM\Column(name="refuse", type="text", nullable=true)
     * @Serializer\Groups({"Default", "api"})
     */
    protected $refuse;

    /**
     * @var ArrayCollection $presences
     * @ORM\OneToMany(targetEntity="Sygefor\Bundle\MyCompanyBundle\Entity\Presence", mappedBy="inscription", cascade={"persist", "remove"})
     * @ORM\OrderBy({"dateBegin" = "ASC"})
     * @Serializer\Groups({"training", "inscription", "api.attendance", "session"})
     */
    protected $presences;

    /**
     * @var Boolean
     * @ORM\Column(name="flag_presence", type="boolean", options={"default":false})
     * @Serializer\Groups({"training", "inscription", "api.attendance", "session"})
     */
    protected $flagPresence;


    /**
     *
     */
    function __construct()
    {
        $this->criteria = new ArrayCollection();
        $this->presences = new ArrayCollection();
    }

    /**
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"api"})
     */
    public function getPrice()
    {
        return $this->isPaying ? $this->getSession()->getPrice() : 0;
    }

    /**
     * @return mixed
     */
    public function getMotivation()
    {
        return $this->motivation;
    }

    /**
     * @param mixed $motivation
     */
    public function setMotivation($motivation)
    {
        $this->motivation = $motivation;
    }

    /**
     * @return mixed
     */
    public function getRefuse()
    {
        return $this->refuse;
    }

    /**
     * @param mixed refuse
     */
    public function setRefuse($refuse)
    {
        $this->refuse = $refuse;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getActiontype()
    {
        return $this->actiontype;
    }

    /**
     * @param mixed $actiontype
     */
    public function setActiontype($actiontype)
    {
        $this->actiontype = $actiontype;
    }

    /**
     * @return mixed
     */
    public function getPresences()
    {
        return $this->presences;
    }

    /**
     * @param mixed presences
     */
    public function setPresences($presences)
    {
        $this->presences = $presences;
    }

    /**
     * @return mixed
     */
    public function getFlagPresence()
    {
        return $this->flagPresence;
    }

    /**
     * @param mixed $flagPresence
     */
    public function setFlagPresence($flagPresence)
    {
        $this->flagPresence = $flagPresence;
    }

    /**
     * Add a noted criterion
     * @param EvaluationNotedCriterion $criterion
     */
    public function addCriterion(EvaluationNotedCriterion $criterion)
    {
        $this->criteria->add($criterion);
    }

    /**
     * Add a presence
     * @param Presence $presence
     */
    public function addPresence(Presence $presence)
    {
        $this->presences->add($presence);
    }


    static public function getFormType()
    {
        return InscriptionType::class;
    }

    function __toString()
    {
        return strval($this->getId());
    }
}
