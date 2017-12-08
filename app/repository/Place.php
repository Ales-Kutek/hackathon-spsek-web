<?php

namespace Repository;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use UW\Core\ORM\Repository\CoreRepository;

/**
 * Place Repository class
 */
class Place extends CoreRepository
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityDao */
	private $dao;


	/**
	 * Place constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->dao = $this->entityManager->getRepository(\Entity\Place::getClassName());
	}

    protected function getJoins(\Kdyby\Doctrine\QueryBuilder &$query)
    {
        $query->addSelect("device")
            ->leftJoin("u.device", "device");
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
