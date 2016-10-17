<?php

namespace App\Repositories;

use App\Entities\Animal;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Utils\DateTime;

class AnimalRepository
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
     * @var SpeciesRepository
     */
    private $speciesRepository;

    /**
     * @var EnclosureRepository
     */
    private $enclosureRepository;


    /**
     * AnimalRepository constructor.
     *
     * @param EntityManager $entityManager
     * @param SpeciesRepository $speciesRepository
     * @param EnclosureRepository $enclosureRepository
     */
    public function __construct(EntityManager $entityManager, SpeciesRepository $speciesRepository, EnclosureRepository $enclosureRepository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Animal::class);
        $this->speciesRepository = $speciesRepository;
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
     * @return Animal|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return Animal[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param Animal $animal
     * @param string $sex
     * @return array
     */
    public function findPotentialParent(Animal $animal = NULL, $sex = Animal::MALE)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select('a.name', 'a.id')
            ->resetDQLPart('from')->from(Animal::class, 'a', 'a.id')
            ->where('a.sex = :sex')
            ->setParameter(':sex', $sex);

        if ($animal) {
            $queryBuilder
                ->andWhere('a.id != :id')
                ->setParameter(':id', $animal->getId());
        }

        return array_map(function ($row) {
                return reset($row);
            }, $queryBuilder->getQuery()->getArrayResult()
        );
    }

    /**
     * @param $values
     * @return Animal
     */
    private function getAnimalIntance($values)
    {
        $animal = NULL;

        if (isset($values->id)) {
            $animal = $this->find($values->id);
        }

        if ( ! $animal) {
            $animal = new Animal();
        }

        return $animal;
    }

    /**
     * @param $values
     * @return Animal
     */
    public function saveFormData($values)
    {
        $animal = $this->getAnimalIntance($values);

        $animal->setName($values->name);
        $animal->setSex($values->sex);
        $animal->setCountry($values->country);
        $animal->setSpecies($this->speciesRepository->find($values->species_id));
        $animal->setEnclosure($this->enclosureRepository->find($values->enclosure_id));

        if ($values->birthday) {
            $animal->setBirthday(new DateTime($values->birthday));
        }
        if ($values->mother_id) {
            $animal->setMother($this->find($values->mother_id));
        }
        if ($values->father_id) {
            $animal->setFather($this->find($values->father_id));
        }

        $this->entityManager->persist($animal);
        $this->entityManager->flush();

        return $animal;
    }


    /**
     * @param $values
     * @return Animal
     */
    public function saveDeathFormData($values)
    {
        $animal = $this->getAnimalIntance($values);

        $animal->setDead(TRUE);
        $animal->setDateOfDeath($values->dateOfDeath ? new DateTime($values->dateOfDeath) : NULL);

        $this->entityManager->persist($animal);
        $this->entityManager->flush();

        return $animal;
    }
}
