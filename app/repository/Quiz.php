<?php

namespace Repository;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use UW\Core\ORM\Repository;

/**
 * Quiz Repository class
 */
class Quiz extends Repository\CoreRepository
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityDao */
	private $dao;


	/**
	 * Quiz constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->dao = $this->entityManager->getRepository(\Entity\Quiz::getClassName());
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
