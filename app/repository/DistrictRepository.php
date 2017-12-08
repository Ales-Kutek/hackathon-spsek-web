<?php

namespace Repository;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use UW\Core\ORM\Repository;

/**
 * District Repository class
 */
class DistrictRepository extends Repository\CoreRepository
{
	/** @var EntityManager */
	private $entityManager;

	/** @var EntityDao */
	private $dao;


	/**
	 * District constructor.
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->dao = $this->entityManager->getRepository(\Entity\District::getClassName());
	}

    public function getAllNonEmpty()
    {
        $query = $this->getBaseQuery();

//        $query->andHaving("count(places.id) != 0");
//        $query->addGroupBy("u.id");

        return $query->getQuery()->getResult();
    }

    protected function getJoins(\Kdyby\Doctrine\QueryBuilder &$query)
    {

        $query->addSelect("places")
            ->leftJoin("u.places", "places");
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
