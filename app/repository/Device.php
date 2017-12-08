<?php

namespace Repository;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use UW\Core\ORM\Repository;

/**
 * Device Repository class
 */
class Device extends Repository\CoreRepository
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityDao */
	private $dao;

	private $minigames = array();

	/**
	 * Device constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager, Pexeso $pexesoRepository, Quiz $quiz)
	{
		$this->entityManager = $entityManager;
		$this->dao = $this->entityManager->getRepository(\Entity\Device::getClassName());
	}

    private function filter(array &$data)
    {
        foreach ($data["minigame"] as $value) {

        }
	}

    public function insertForm(array $data)
    {
        $this->filter($data);

        return parent::insertForm($data);
    }

    public function updateForm(array $data, $id = NULL)
    {
        $this->filter($data);

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
