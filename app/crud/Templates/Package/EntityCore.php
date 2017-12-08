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

class EntityCore extends Base implements ITemplate
{
    public function generate($name)
    {
        $fullName = $this->getFullName($name);

        $str = '
namespace UW\\'  . $name . '\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use UW\Core\Components\Form\Dropzone\Metadata\DropzoneEntity;
use UW\Core\Components\Form\Dropzone\Metadata\DropzoneFieldMetadata;
use UW\Core\Components\Form\Dropzone\Metadata\DropzoneMetadata;
use UW\Core\ORM\BaseEntity;
use UW\Core\ORM\TIdentifer;
use UW\Publish\IPublicable;
use UW\Publish\TPublish;
use Gedmo\Mapping\Annotation as Gedmo;
use Nette\Reflection\AnnotationsParser;
use Nette\Utils\Strings;
use UW\Search\Suggest\ISuggestItem;
use UW\Search\TSearchLinkShortcut;

/**
 * @ORM\MappedSuperclass()
 */
class '. $name .'EntityCore extends BaseEntity
{
    use TIdentifer;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;
}
';

        $this->createTemplate($str, $name);
    }
}