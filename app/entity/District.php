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
 * District
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class District extends BaseEntity
{

	/** @ORM\Column(type="string") */
	protected $title;

	/**
	 * @OneToMany(targetEntity="Places", cascade={"persist"}, mappedBy="district")
	 * @JoinColumn(name="places", referencedColumnName="district")
	 */
	protected $places = array();

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return District
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @param mixed $places
     * @return District
     */
    public function setPlaces($places)
    {
        $this->places = $places;
        return $this;
    }


}
