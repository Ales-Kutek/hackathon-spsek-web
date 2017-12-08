<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 10/8/17
 * Time: 2:19 PM
 */

namespace UW\Core\Crud\Templates\Package;


use UW\Core\Crud\ITemplate;
use UW\Core\Crud\Templates\Base;

class Repository extends Base implements ITemplate
{
    public function generate($name)
    {
        $str = '
namespace UW\\' . $name . '\Repository;

use UW\Core\App\ConfigParameters;
use UW\Core\App\Helper\TModuleCodeName;
use UW\FileManager\FileHandler\FileHandler;
use UW\Core\App\Link\Link;
use UW\Core\App\Helper\ArrayMapper;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\QueryBuilder;
use Nette\Utils\ArrayHash;
use UW\Category\ICategoryable;
use Entity\\'. $name .';
use UW\Core\ORM\Repository\CoreRepository;
use UW\Core\ORM\Repository\RepositoryParameters;
use UW\Search\Suggest\ISuggestItem;
use UW\Search\Suggest\SuggestList;

/**
 * '. $name .'Repository Repository class
 */
class '. $name .'Repository extends CoreRepository
{
    use TModuleCodeName;

	/** @var EntityManager @inject */
	public $entityManager;

    /**
     * @var ConfigParameters @inject
     */
    public $configParameters;

    /**
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		return $this->entityManager;
	}

	/**
	 * @return EntityDao
	 */
	public function getDao()
	{
		return $this->entityManager->getRepository('. $name .'::getClassName());
	}

    /**
     * @return RepositoryParameters|null
     */
    public function getParameters(): ?RepositoryParameters
    {
        return new RepositoryParameters($this->moduleCodeName, $this->configParameters->components->{$this->moduleCodeName});
    }
}
';

        $this->createTemplate($str, $name);
    }
}