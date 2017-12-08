<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 1:59 PM
 */

namespace UW\Core\Crud\Templates\Package;


use Core\Crud\Templates\Base;
use UW\Core\Crud\ITemplate;

class ExampleEntity extends Base implements ITemplate
{

    public function generate($name)
    {
        $fullName = $this->getFullName($name);

        $str = '<?php
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
use UW\\' . $name . '\Entity\\' . $name . 'EntityCore;
use Gedmo\Mapping\Annotation as Gedmo;
use Nette\Reflection\AnnotationsParser;
use Nette\Utils\Strings;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class '. $name . ' extends ' . $name .'EntityCore
{
}
';

        $this->createTemplate($str, $name);
    }
}