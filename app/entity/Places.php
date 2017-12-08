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
 * Places
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Places extends BaseEntity
{
	/** @ORM\Column(type="string") */
	protected $title;

    /**
    * Relation for District
    * @ManyToOne(targetEntity="District")
    * @JoinColumn(name="district", referencedColumnName="id")
    */
    protected $district;
}
