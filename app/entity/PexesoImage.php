<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 08/12/17
 * Time: 20:27
 */



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

/**
 * Pexeso
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class PexesoImage extends BaseEntity
{
    /** @ORM\Column(type="string") */
    protected $file_path;

    /**
    * Relation for Pexeso
    * @ManyToOne(targetEntity="Pexeso")
    * @JoinColumn(name="pexeso", referencedColumnName="id")
    */
    protected $pexeso;

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->file_path;
    }

    /**
     * @param mixed $file_path
     * @return PexesoImage
     */
    public function setFilePath($file_path)
    {
        $this->file_path = $file_path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPexeso()
    {
        return $this->pexeso;
    }

    /**
     * @param mixed $pexeso
     * @return PexesoImage
     */
    public function setPexeso($pexeso)
    {
        $this->pexeso = $pexeso;
        return $this;
    }
}
