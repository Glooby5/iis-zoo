<?php

namespace App\Repositories;

use App\Entities\Species;
use App\Entities\SpeciesCertificate;
use App\Entities\User;
use App\Forms\CertificateFormFactory;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Utils\DateTime;

class SpeciesCertificateRepository extends CertificateRepository
{
    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManager $entityManager, SpeciesRepository $enclosureTypeRepository, UserRepository $userRepository)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(SpeciesCertificate::class);
        $this->speciesRepository = $enclosureTypeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $values
     * @return SpeciesCertificate
     */
    public function saveFormData($values)
    {
        $speciesCertificate = NULL;

        if (isset($values->id)) {
            $speciesCertificate = $this->find($values->id);
        }

        if ( ! $speciesCertificate) {
            $speciesCertificate = new SpeciesCertificate();
        }

        $speciesCertificate->setSpecies($this->speciesRepository->find($values->species_id));
        $speciesCertificate->setUser($this->userRepository->find($values->user_id));
        $speciesCertificate->setName($values->name);

        if ($values->start) {
            $speciesCertificate->setStart(new DateTime($values->start));
        }

        if ($values->end) {
            $speciesCertificate->setEnd(new DateTime($values->end));
        }


        $this->entityManager->persist($speciesCertificate);
        $this->entityManager->flush();

        return $speciesCertificate;
    }

    public function canUserFeedSpecies($userId, $speciesId, $date)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select('c')
            ->from(SpeciesCertificate::class, 'c')
            ->where('c.user = :user')
                ->setParameter(':user', $userId)
            ->andWhere('c.species = :species')
                ->setParameter(':species', $speciesId)
            ->andWhere('c.start < :start')
                ->setParameter(':start', $date)
            ->andWhere('c.end > :end')
                ->setParameter(':end', $date);

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
