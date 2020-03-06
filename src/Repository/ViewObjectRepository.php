<?php

namespace App\Repository;

use App\Entity\ViewObject;
use App\Service\ViewObjectInterpreter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ViewObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewObject[]    findAll()
 * @method ViewObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewObject::class);
    }

    public function insert(ViewObject $viewObject)
    {
        $existingViewObject = $this->findOneBy([
            'mediaLink' => $viewObject->getMediaLink(),
            'minuteLink' => $viewObject->getMinuteLink(),
        ]);

        if($existingViewObject instanceof ViewObject) {
            return $existingViewObject;
        }

        $viewObject = ViewObjectInterpreter::processViewObject($viewObject);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($viewObject);
        $entityManager->flush();

        return $viewObject;
    }
}
