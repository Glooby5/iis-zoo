<?php

namespace App\Repositories;

use App\Entities\Species;
use App\Entities\SpeciesCertificate;
use App\Entities\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

abstract class CertificateRepository
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * SpeciesRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        //$this->repository = $this->entityManager->getRepository(SpeciesCertificate::class);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param $id
     * @return SpeciesCertificate|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return SpeciesCertificate[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return SpeciesCertificate[]
     */
    public function findPairs()
    {
        return $this->repository->findPairs([], 'name');
    }

    /**
     * @param $values
     * @return SpeciesCertificate
     */
    public function saveFormData($values)
    {
        $species = NULL;

        if (isset($values->id)) {
            $species = $this->find($values->id);
        }

        if ( ! $species) {
            $species = new Species();
        }

        $species->setName($values->name);
        $species->setOccurrence($values->occurrence);

        $this->entityManager->persist($species);
        $this->entityManager->flush();

        return $species;
    }
}
