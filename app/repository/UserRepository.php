<?php

/**
 * UserRepository repository
 */

namespace Repository;

use UW\Core\ORM\Repository\CoreRepository;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;

/**
 * UserRepository repository class
 */
class UserRepository extends CoreRepository {
    /**
     * @var EntityManager @inject
     */
    public $entityManager;

    /**
     * UserRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /*
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
        return $this->getEntityManager()->getRepository(\Entity\User::getClassName());
    }
}
