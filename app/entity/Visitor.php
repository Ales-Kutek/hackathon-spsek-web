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
 * Visitor
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Visitor extends BaseEntity
{
	/** @ORM\Column(type="string") */
	protected $title;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $score = 0;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Visitor
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return Visitor
     */
    public function setScore(int $score)
    {
        $this->score = $score;
        return $this;
    }
}
