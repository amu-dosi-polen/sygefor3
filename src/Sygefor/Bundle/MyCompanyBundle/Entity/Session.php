<?php

namespace Sygefor\Bundle\MyCompanyBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Sygefor\Bundle\TrainingBundle\Entity\Session\AbstractSession;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sygefor\Bundle\MyCompanyBundle\Form\SessionType;
use Sygefor\Bundle\MyCompanyBundle\Entity\DateSession;
use Sygefor\Bundle\MyCompanyBundle\Entity\Module;

/**
 *
 * @ORM\Table(name="session")
 * @ORM\Entity
 */
class Session extends AbstractSession
{
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $name;

    /**
     * @var Module
     * @ORM\ManyToOne(targetEntity="Sygefor\Bundle\MyCompanyBundle\Entity\Module", inversedBy="sessions")
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id", nullable=true)
     * @Serializer\Groups({"session", "api.training", "api.inscription"})
     */
    protected $module;

    /**
     * Used for session creation form only.
     *
     * @var Module
     */
    protected $newModule;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $teachingCost;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $vacationCost;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $accommodationCost;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $mealCost;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $transportCost;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $materialCost;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Serializer\Groups({"session", "inscription", "api"})
     */
    protected $taking;

    /**
     * @var ArrayCollection $dates
     * @ORM\OneToMany(targetEntity="Sygefor\Bundle\MyCompanyBundle\Entity\DateSession", mappedBy="session", cascade={"persist", "remove"})
     * @ORM\OrderBy({"dateBegin" = "ASC"})
     * @Serializer\Groups({"session", "api.session"})
     */
    protected $dates;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule($module)
    {
        $this->module = $module;
        if ($module) {
            $this->training->addModule($module);
        }
    }

    /**
     * @return Module
     */
    public function getNewModule()
    {
        return $this->newModule;
    }

    /**
     * @param Module $newModule
     */
    public function setNewModule($newModule)
    {
        $this->newModule = $newModule;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getTeachingCost()
    {
        return $this->teachingCost;
    }

    /**
     * @param mixed $teachingCost
     */
    public function setTeachingCost($teachingCost)
    {
        $this->teachingCost = $teachingCost;
    }

    /**
     * @return mixed
     */
    public function getVacationCost()
    {
        return $this->vacationCost;
    }

    /**
     * @param mixed $vacationCost
     */
    public function setVacationCost($vacationCost)
    {
        $this->vacationCost = $vacationCost;
    }

    /**
     * @return mixed
     */
    public function getAccommodationCost()
    {
        return $this->accommodationCost;
    }

    /**
     * @param mixed $accommodationCost
     */
    public function setAccommodationCost($accommodationCost)
    {
        $this->accommodationCost = $accommodationCost;
    }

    /**
     * @return mixed
     */
    public function getMealCost()
    {
        return $this->mealCost;
    }

    /**
     * @param mixed $mealCost
     */
    public function setMealCost($mealCost)
    {
        $this->mealCost = $mealCost;
    }

    /**
     * @return mixed
     */
    public function getTransportCost()
    {
        return $this->transportCost;
    }

    /**
     * @param mixed $transportCost
     */
    public function setTransportCost($transportCost)
    {
        $this->transportCost = $transportCost;
    }

    /**
     * @return mixed
     */
    public function getMaterialCost()
    {
        return $this->materialCost;
    }

    /**
     * @param mixed $materialCost
     */
    public function setMaterialCost($materialCost)
    {
        $this->materialCost = $materialCost;
    }

    /**
     * @return mixed
     */
    public function getTaking()
    {
        return $this->taking;
    }

    /**
     * @param mixed $taking
     */
    public function setTaking($taking)
    {
        $this->taking = $taking;
    }

    /**
     * @return ArrayCollection
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * @param ArrayCollection $dates
     */
    public function setDates($dates)
    {
        $this->dates = $dates;
    }
    /**
     * @param DateSession $dates
     *
     * @return bool
     */
    public function addDates($dates)
    {
        if (!$this->dates->contains($dates)) {
            $this->dates->add($dates);

            return true;
        }

        return false;
    }

    /**
     * @param DateSession $dates
     *
     * @return bool
     */
    public function removeDate($dates)
    {
        if ($this->dates->contains($dates)) {
            $this->dates->removeElement($dates);

            return true;
        }

        return false;
    }

    function __construct()
    {
        $this->dates          = new ArrayCollection();
    }

    public function __clone()
    {
        $this->setId(null);
        $this->dates         = new ArrayCollection();
    }

    /**
     * @Serializer\VirtualProperty
     *
     * @param $front_root_url
     * @param $apiSerialization
     *
     * @return string
     * @return string
     */
    public function getFrontUrl($front_root_url = 'http://sygefor.dev', $apiSerialization = false)
    {
        $url = $front_root_url . '/training/' . $this->getTraining()->getId() . '/';
        if (!$apiSerialization) {
            // URL permitting to register a private session
            if ($this->getRegistration() === self::REGISTRATION_PRIVATE && (!method_exists($this, 'getModule') || !$this->getModule())) {
                return $url . $this->getId() . '/' . md5($this->getId() + $this->getTraining()->getId());
            }
            // URL permitting to register a module sessions
            else if (method_exists($this, 'getModule') && $this->getModule()) {
                return $url . '/' . md5($this->training->getType() . $this->getTraining()->getId());
            }
        }

        // return public URL
        return $url . $this->getId();
    }

    function __toString()
    {
        $name = $this->getName() ? $this->getName() : $this->getTraining()->getName();

        return $name . " - " . $this->getDateRange();
    }

    public static function getFormType()
    {
        return SessionType::class;
    }
}
