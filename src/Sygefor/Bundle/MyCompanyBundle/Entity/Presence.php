<?php
/**
 * Created by PhpStorm.
 * User: erwan
 * Date: 9/14/16
 * Time: 5:33 PM
 */

namespace Sygefor\Bundle\MyCompanyBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="presence")
 * @ORM\Entity
 */
class Presence
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"Default", "api"})
     */
    protected $id;

    /**
     * @ORM\Column(name="dateBegin", type="datetime")
     * @Assert\NotBlank(message="Vous devez préciser une date de début.")
     * @Serializer\Groups({"Default", "api"})
     */
    protected $dateBegin;

    /**
     * @ORM\Column(name="morning", type="string", length=512, nullable=true)
     * @var String
     */
    protected $morning;

    /**
     * @ORM\Column(name="afternoon", type="string", length=512, nullable=true)
     * @var String
     */
    protected $afternoon;

    /**
     * @var Session
     * @ORM\ManyToOne(targetEntity="Inscription", inversedBy="presences")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Serializer\Groups({"session", "inscription", "trainee", "trainer", "api"})
     */
    protected $inscription;

    public function __construct()
    {
        $this->session = new ArrayCollection();
    }

    public function __clone()
    {
        $this->session = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDateBegin()
    {
        return $this->dateBegin;
    }

    /**
     * @param mixed $dateBegin
     */
    public function setDateBegin($dateBegin)
    {
        $this->dateBegin = $dateBegin;
    }

    /**
     * @return mixed
     */
    public function getMorning()
    {
        return $this->morning;
    }

    /**
     * @param mixed $morning
     */
    public function setMorning($morning)
    {
        $this->morning = $morning;
    }

    /**
     * @return mixed
     */
    public function getAfternoon()
    {
        return $this->afternoon;
    }

    /**
     * @param mixed $afternoon
     */
    public function setAfternoon($afternoon)
    {
        $this->afternoon = $afternoon;
    }

    /**
     * @return ArrayCollection
     */
    public function getInscription()
    {
        return $this->inscription;
    }

    /**
     * @param mixed $inscription
     */
    public function setInscription($inscription)
    {
        $this->inscription = $inscription;
    }


}