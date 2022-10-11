<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\Entity\User\User;
use Doctrine\ORM\ORMInvalidArgumentException;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method User|null getUser()
 */
class AdminCRUDController extends CRUDController
{
    public function batchActionDelete(ProxyQueryInterface $query): Response
    {
        try {
            return parent::batchActionDelete($query);
        } catch (ORMInvalidArgumentException) {
        }

        return $this->redirectToList();
    }
}
