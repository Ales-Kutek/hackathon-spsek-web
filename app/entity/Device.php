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
 * Device
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Device extends BaseEntity
{
	use TIdentifer;

	/** @ORM\Column(type="string", nullable=true) */
	protected $title;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $code;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Device
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Device
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

}
