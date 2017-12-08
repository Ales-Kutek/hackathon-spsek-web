<?php
/**
 * UserRepository Entity
 */

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToOne;
use Entity\BaseEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Kdyby\Doctrine\Entities\Attributes\Identifier;


/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseEntity implements \Nette\Security\IIdentity
{
    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    protected $email;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    protected $surname;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salutation;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $frontDegree;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $afterDegree;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     */
    protected $hash;

    public function __construct() {
        parent::__construct();
    }

    public function getFullName() {
        return "{$this->frontDegree} {$this->name} {$this->surname} {$this->afterDegree}";
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->getFullName();
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param string $salutation
     */
    public function setSalutation(string $salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * @return string
     */
    public function getFrontDegree()
    {
        return $this->frontDegree;
    }

    /**
     * @param string $frontDegree
     */
    public function setFrontDegree(string $frontDegree)
    {
        $this->frontDegree = $frontDegree;
    }

    /**
     * @return string
     */
    public function getAfterDegree()
    {
        return $this->afterDegree;
    }

    /**
     * @param string $afterDegree
     */
    public function setAfterDegree(string $afterDegree)
    {
        $this->afterDegree = $afterDegree;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked(bool $blocked)
    {
        $this->blocked = $blocked;
    }
    
    /**
     * Zahashuje heslo pomocí BCRYPT
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT,
            array(
            "cost" => 10
        ));
    }
    
    /**
     * Vytvoří hash pro obnovu hesla
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = password_hash($hash, PASSWORD_BCRYPT,
            array(
            "cost" => 10
        ));
    }
    
    public function setRole($role) {
        $this->role = $role;
    }
    
    /*
     * Získá role uživatele
     */
    public function getRoles() {
        return array();
    }
    
    /*
     * Získá ID číslo uživatele
     */
    public function getId() {
        return $this->id;
    }


}