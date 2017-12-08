<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 7/11/17
 * Time: 2:06 PM
 */

namespace Entity;


trait TIdentifer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __clone()
    {
        $this->id = NULL;
    }
}