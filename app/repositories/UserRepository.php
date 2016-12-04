<?php

namespace App\Repositories;

use App\Entities\Cleaning;
use App\Entities\Feeding;
use App\Entities\User;
use Doctrine\ORM\Mapping;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class UserRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * UserRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @param $id
     * @return User|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $options
     * @return User|null
     */
    public function findOneBy(array $options)
    {
        return $this->repository->findOneBy($options);
    }

    /**
     * @param array $options
     * @return User[]|null
     */
    public function findBy(array $options)
    {
        return $this->repository->findBy($options);
    }

    /**
     * @return User[]
     */
    public function findPairs()
    {
        return $this->repository->findPairs([], 'lastname');
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function getUserFeedingsInTime($userId, $start, $end, $editing = null)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select('f')
            ->from(Feeding::class, 'f')
            ->where('f.keeper = :user')
            ->setParameter(':user', $userId)
            ->andWhere('f.done = FALSE');

        $queryBuilder
            ->andwhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->between('f.start', ':start', ':end'),
                $queryBuilder->expr()->between('f.end', ':start', ':end')
            ))
            ->setParameter(':start', $start)
            ->setParameter(':end', $end);

        if ($editing) {
            $queryBuilder->andWhere('f.id != :id')
                ->setParameter(':id', $editing);
        }

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function getUserCleaningsInTime($userId, $start, $end, $editing = null)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select('c')
            ->from(Cleaning::class, 'c')
            ->innerJoin('c.cleaners', 'u')
            ->where('u.id = :user')
            ->setParameter(':user', $userId)
            ->andWhere('c.done = FALSE');

        $queryBuilder
            ->andwhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->between('c.start', ':start', ':end'),
                $queryBuilder->expr()->between('c.end', ':start', ':end')
            ))
            ->setParameter(':start', $start)
            ->setParameter(':end', $end);

        if ($editing) {
            $queryBuilder->andWhere('c.id != :id')
                ->setParameter(':id', $editing);
        }

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function hasUserFreeTime($userId, $start, $end, $editingFeeding = null, $editingCleaning = null)
    {
        $feedings = count($this->getUserFeedingsInTime($userId, $start, $end, $editingFeeding));
        $cleanings = count($this->getUserCleaningsInTime($userId, $start, $end, $editingCleaning));

        return !$feedings && ! $cleanings;
    }
}
