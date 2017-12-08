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
 * Pexeso
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class pexeso extends BaseEntity
{
	/** @ORM\Column(type="string") */
	protected $title;

	/**
	 * @OneToMany(targetEntity="PexesoImage", cascade={"persist"}, mappedBy="pexeso")
	 * @JoinColumn(name="pexeso_image", referencedColumnName="pexeso")
	 */
	protected $pexeso_image = array();

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Pexeso
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPexesoImage()
    {
        return $this->pexeso_image;
    }

    /**
     * @param mixed $pexeso_image
     * @return Pexeso
     */
    public function setPexesoImage($pexeso_image)
    {
        $this->pexeso_image = $pexeso_image;
        return $this;
    }
}
