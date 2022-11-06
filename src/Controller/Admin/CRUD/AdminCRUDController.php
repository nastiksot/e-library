<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\Entity\User\User;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Doctrine\ORM\ORMInvalidArgumentException;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @method User|null getUser()
 */
class AdminCRUDController extends CRUDController
{
    protected ExceptionFactory  $exceptionFactory;
    protected MessageBusHandler $messageBusHandler;

    #[Required]
    public function init(
        ExceptionFactory $exceptionFactory,
        MessageBusHandler $messageBusHandler,
    ): void {
        $this->exceptionFactory  = $exceptionFactory;
        $this->messageBusHandler = $messageBusHandler;
    }

    public function batchActionDelete(ProxyQueryInterface $query): Response
    {
        try {
            return parent::batchActionDelete($query);
        } catch (ORMInvalidArgumentException) {
        }

        return $this->redirectToList();
    }
}
