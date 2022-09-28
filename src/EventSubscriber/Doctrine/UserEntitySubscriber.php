<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Contracts\Entity\UserInterface;
use App\Repository\DealerRequest\DealerRequestRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class UserEntitySubscriber implements EventSubscriberInterface
{
//    public function __construct(
//        private DealerRequestRepository $dealerRequestRepository
//    ) {
//    }
//
//    public function preRemove(LifecycleEventArgs $args)
//    {
//        $entity = $args->getEntity();
//
//        if ($entity instanceof UserInterface) {
//            $deletedUserId = $entity->getId();
//
//            if (null !== $deletedUserId) {
//                $this->dealerRequestRepository->resetUserDataOnDeleteUser($deletedUserId);
//            }
//        }
//    }
//
    public function getSubscribedEvents()
    {
        return [Events::preRemove];
    }
}
