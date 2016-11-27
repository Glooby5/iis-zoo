<?php

namespace App\Repositories;

use App\Entities\EnclosureTypeCertificate;
use App\Entities\Species;
use App\Entities\SpeciesCertificate;
use App\Entities\User;
use App\Forms\CertificateFormFactory;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Utils\DateTime;

class EnclosureTypeCertificateRepository extends CertificateRepository
{
    /**
     * @var EnclosureTypeRepository
     */
    private $enclosureTypeRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManager $entityManager, EnclosureTypeRepository $enclosureTypeRepository, UserRepository $userRepository)
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(EnclosureTypeCertificate::class);
        $this->enclosureTypeRepository = $enclosureTypeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $values
     * @return EnclosureTypeCertificate
     */
    public function saveFormData($values)
    {
        $speciesCertificate = NULL;

        if (isset($values->id)) {
            $speciesCertificate = $this->find($values->id);
        }

        if ( ! $speciesCertificate) {
            $speciesCertificate = new EnclosureTypeCertificate();
        }

        $speciesCertificate->setEnclosureType($this->enclosureTypeRepository->find($values->enclosure_type_id));
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

    public function canUserEnclosureType($userId, $enclosureTypeId, $date)
    {
        $queryBuilder = $this->repository->createQueryBuilder()
            ->select('c')
            ->from(EnclosureTypeCertificate::class, 'c')
            ->where('c.user = :user')
            ->setParameter(':user', $userId)
            ->andWhere('c.enclosureType = :enclosureType')
            ->setParameter(':enclosureType', $enclosureTypeId)
            ->andWhere('c.start < :start')
            ->setParameter(':start', $date)
            ->andWhere('c.end > :end')
            ->setParameter(':end', $date);

        return count($queryBuilder->getQuery()->getArrayResult());
    }
}
