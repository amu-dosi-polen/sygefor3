<?php

namespace Sygefor\Bundle\MyCompanyBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sygefor\Bundle\TraineeBundle\Entity\Term\PublicType;
use Sygefor\Bundle\TrainingBundle\Entity\Training\AbstractTraining;
use Sygefor\Bundle\MyCompanyBundle\Form\InternshipType;

/**
 * Stage.
 *
 * @ORM\Entity
 * @ORM\Table(name="internship")
 */
class Internship extends AbstractTraining
{
    /**
     * @ORM\ManyToMany(targetEntity="Sygefor\Bundle\TraineeBundle\Entity\Term\PublicType")
     * @ORM\JoinTable(name="internship__internship_public_type",
     *      joinColumns={@ORM\JoinColumn(name="intership_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="public_type_id", referencedColumnName="id")}
     * )
     * @Serializer\Groups({"training", "inscription", "api"})
     */
    protected $publicTypes;

    /**
     * @ORM\ManyToMany(targetEntity="Sygefor\Bundle\TraineeBundle\Entity\Term\PublicType")
     * @ORM\JoinTable(name="internship__internship_public_type_restrict",
     *      joinColumns={@ORM\JoinColumn(name="intership_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="public_type_restrict_id", referencedColumnName="id")}
     * )
     * @Serializer\Groups({"training", "inscription", "api"})
     */
    protected $publicTypesRestrict;

    /**
     * @var string
     * @ORM\Column(name="prerequisites", type="text", nullable=true)
     * @Serializer\Groups({"training", "api"})
     */
    protected $prerequisites;

    public function __construct()
    {
        $this->publicTypes = new ArrayCollection();
        $this->publicTypesRestrict = new ArrayCollection();

        parent::__construct();
    }

    public function __clone()
    {
        $this->publicTypes = new ArrayCollection();
        $this->publicTypesRestrict = new ArrayCollection();

        parent::__construct();
    }

    /**
     * @param mixed $publicTypes
     */
    public function setPublicTypes($publicTypes)
    {
        $this->publicTypes = $publicTypes;
    }

    /**
     * @return mixed
     */
    public function getPublicTypes()
    {
        return $this->publicTypes;
    }

    /**
     * @param PublicType $publicType
     */
    public function addPublicType($publicType)
    {
        if (!$this->publicTypes->contains($publicType)) {
            $this->publicTypes->add($publicType);
        }
    }

    /**
     * @param PublicType $publicType
     */
    public function removePublicType($publicType)
    {
        if ($this->publicTypes->contains($publicType)) {
            $this->publicTypes->removeElement($publicType);
        }
    }

    /**
     * HumanReadablePropertyAccessor helper : provides a list of public types as string
     * @return String
     */
    public function getPublicTypesListString()
    {
        if (empty($this->publicTypes)) return "";
        $ptNames = array();
        foreach ($this->publicTypes as $pt) {
            $ptNames[] = $pt->getName();
        }

        return implode (", ",$ptNames);
    }

    /**
     * @param mixed $publicTypesRestrict
     */
    public function setPublicTypesRestrict($publicTypesRestrict)
    {
        $this->publicTypesRestrict = $publicTypesRestrict;
    }

    /**
     * @return mixed
     */
    public function getPublicTypesRestrict()
    {
        return $this->publicTypesRestrict;
    }

    /**
     * @param PublicTypeRestrict $publicTypesRestrict
     */
    public function addPublicTypeRestrict($publicTypesRestrict)
    {
        if (!$this->publicTypesRestrict->contains($publicTypesRestrict)) {
            $this->publicTypesRestrict->add($publicTypesRestrict);
        }
    }

    /**
     * @param PublicTypeRestrict $publicTypesRestrict
     */
    public function removePublicTypeRestrict($publicTypesRestrict)
    {
        if ($this->publicTypesRestrict->contains($publicTypesRestrict)) {
            $this->publicTypesRestrict->removeElement($publicTypesRestrict);
        }
    }

    /**
     * HumanReadablePropertyAccessor helper : provides a list of public types as string
     * @return String
     */
    public function getPublicTypesRestrictListString()
    {
        if (empty($this->publicTypesRestrict)) return "";
        $ptNames = array();
        foreach ($this->publicTypesRestrict as $pt) {
            $ptNames[] = $pt->getName();
        }

        return implode (", ",$ptNames);
    }

    /**
     * @return mixed
     */
    public function getPrerequisites()
    {
        return $this->prerequisites;
    }

    /**
     * @param mixed $prerequisites
     */
    public function setPrerequisites($prerequisites)
    {
        $this->prerequisites = $prerequisites;
    }

    /**
     * @return string
     */
    static public function getType()
    {
        return 'internship';
    }

    /**
     * @return string
     */
    static public function getTypeLabel()
    {
        return 'Stage';
    }

    /**
     * @return string
     */
    static public function getFormType()
    {
        return InternshipType::class;
    }
}
