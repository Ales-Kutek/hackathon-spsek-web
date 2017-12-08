<?php

namespace Repository;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Http\FileUpload;
use UW\Core\ORM\Repository;

/**
 * Pexeso Repository class
 */
class Pexeso extends Repository\CoreRepository
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityDao */
	private $dao;


	/**
	 * Pexeso constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->dao = $this->entityManager->getRepository(\Entity\Pexeso::getClassName());
	}

    private function updateFiles(array &$data)
    {
        /**
         * @var int $key
         * @var FileUpload $value
         */
        foreach ($data["pexeso_image"] as $key => &$value) {
            $value->move(WWW_DIR . DS . "files" . DS . $value->getName());
            $value = array("file_path" => $value->getName());
        }
    }

    public function insertForm(array $data)
    {
        $this->updateFiles($data);

        return parent::insertForm($data);
    }

    public function updateForm(array $data, $id = NULL)
    {
        $this->updateFiles($data);

        return parent::updateForm($data, $id);
    }

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
		return $this->dao;
	}
}
