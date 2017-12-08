<?php

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Place
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Place extends BaseEntity
{
	/** @ORM\Column(type="string") */
	protected $title;
	
	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description;

    /**
     * @ManyToMany(targetEntity="Device")
     * @JoinTable(name="place_user",
     *      joinColumns={@JoinColumn(name="place_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="device_id", referencedColumnName="id")}
     *      )
     */
    private $device;

    public function __construct()
    {
        parent::__construct();

        $this->device = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Place
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param mixed $device
     * @return Place
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Place
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }
}
