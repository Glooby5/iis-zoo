<?php

namespace App\Repositories;

use App\Entities\CleaningTypeCertificate;
use App\Entities\EnclosureTypeCertificate;
use App\Entities\Species;
use App\Entities\SpeciesCertificate;
use App\Entities\User;
use App\Forms\CertificateFormFactory;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Utils\DateTime;

class CleaningTypeCertificateRepository extends CertificateRepository
{
    /**
     * @var CleaningTypeRepository
     */
    private $cleaningTypeRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManager $entityManager, CleaningTypeRepository $enclosureTypeRepository, UserRepository $userRepository)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(CleaningTypeCertificate::class);
        $this->cleaningTypeRepository = $enclosureTypeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $values
     * @return CleaningTypeCertificate
     */
    public function saveFormData($values)
    {
        $speciesCertificate = NULL;

        if (isset($values->id)) {
            $speciesCertificate = $this->find($values->id);
        }

        if ( ! $speciesCertificate) {
            $speciesCertificate = new CleaningTypeCertificate();
        }

        $speciesCertificate->setCleaningType($this->cleaningTypeRepository->find($values->cleaning_type_id));
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

    public function canUserDoCleaningType($userId, $cleaningTypeId, $date)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select('c')
            ->from(CleaningTypeCertificate::class, 'c')
            ->where('c.user = :user')
            ->setParameter(':user', $userId)
            ->andWhere('c.cleaningType = :cleaningType')
            ->setParameter(':cleaningType', $cleaningTypeId)
            ->andWhere('c.start < :start')
            ->setParameter(':start', $date)
            ->andWhere('c.end > :end')
            ->setParameter(':end', $date);

        return count($queryBuilder->getQuery()->getArrayResult());
    }
}
