<?php

namespace Repository;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use UW\Core\ORM\Repository;

/**
 * Places Repository class
 */
class PlacesRepository extends Repository\CoreRepository
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityDao */
	private $dao;


	/**
	 * Places constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->dao = $this->entityManager->getRepository(\Entity\Places::getClassName());
	}

    protected function getJoins(\Kdyby\Doctrine\QueryBuilder &$query)
    {
        $query->addSelect("district")
            ->leftJoin("u.district", "district");
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
