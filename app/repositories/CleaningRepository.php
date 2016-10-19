<?php

namespace App\Repositories;

use App\Entities\Cleaning;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Utils\DateTime;

class CleaningRepository
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
     * @var CleaningTypeRepository
     */
    private $cleaningTypeRepository;
    /**
     * @var EnclosureRepository
     */
    private $enclosureRepository;

    /**
     * CleaningRepository constructor.
     *
     * @param EntityManager $entityManager
     * @param CleaningTypeRepository $cleaningTypeRepository
     * @param EnclosureRepository $enclosureRepository
     */
    public function __construct
    (
        EntityManager $entityManager,
        CleaningTypeRepository $cleaningTypeRepository,
        EnclosureRepository $enclosureRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Cleaning::class);
        $this->cleaningTypeRepository = $cleaningTypeRepository;
        $this->enclosureRepository = $enclosureRepository;
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
     * @return Cleaning|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return Cleaning[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return Cleaning[]
     */
    public function findPairs()
    {
        return $this->repository->findPairs([], 'name');
    }

    public function saveFormData($values)
    {
        $cleaning = NULL;

        if (isset($values->id)) {
            $cleaning = $this->find($values->id);
        }

        if ( ! $cleaning) {
            $cleaning = new Cleaning();
        }

        $cleaning->setAttendantsCount($values->attendants_count);
        $cleaning->setDone($values->done);
        $cleaning->setEnclosure($this->enclosureRepository->find($values->enclosure_id));
        $cleaning->setCleaningType($this->cleaningTypeRepository->find($values->cleaning_type_id));

        if ($values->start) {
            $cleaning->setStart(new DateTime($values->start));
        }

        if ($values->end) {
            $cleaning->setEnd(new DateTime($values->end));
        }

        $this->entityManager->persist($cleaning);
        $this->entityManager->flush();

        return $cleaning;
    }
}
