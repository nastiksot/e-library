<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\CQ\Command\Order\CancelBookOrderCommand;
use App\CQ\Command\Order\DoneBookOrderCommand;
use App\Entity\Order;
use App\Entity\User\User;
use App\Service\MessageBusHandler;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @method User|null getUser()
 */
final class OrderCRUDController extends CRUDController
{
    public function cancelAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): Response {
        /** @var Order $book */
        $order = $this->assertObjectExists($request, true);
        if (!$order) {
            throw $this->createNotFoundException('Order Not Found');
        }

        $this->admin->checkAccess('cancel', $order);
        $this->admin->setSubject($order);

        if ($request->getMethod() === Request::METHOD_POST) {
            // check the csrf token
            $this->validateCsrfToken($request, 'admin.order.cancel');

            try {
                /** @var Order $order */
                $order = $messageBusHandler->handleCommand(
                    new CancelBookOrderCommand(
                        orderId: (int)$order->getId(),
                    )
                );

                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans(
                        'ORDER_ENTITY.MESSAGE.CANCEL.SUCCESS',
                        [
                            '%order%' => $order->getId(),
                        ],
                        'SonataAdminBundle'
                    )
                );

                // redirect to list
                return $this->redirectToRoute('admin_order_list');
            } catch (Throwable $e) {
                $errorMessage = $e->getPrevious()?->getMessage() ?? $e->getMessage();
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans($errorMessage, [], $this->admin->getTranslationDomain())
                );
            }
        }

        return $this->renderWithExtraParams('admin/order/cancel.html.twig', [
            'object'     => $order,
            'order'      => $order,
            'action'     => 'cancel',
            'csrf_token' => $this->getCsrfToken('admin.order.cancel'),
        ]);
    }

    public function doneAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): Response {
        /** @var Order $book */
        $order = $this->assertObjectExists($request, true);
        if (!$order) {
            throw $this->createNotFoundException('Order Not Found');
        }

        $this->admin->checkAccess('done', $order);
        $this->admin->setSubject($order);

        if ($request->getMethod() === Request::METHOD_POST) {
            // check the csrf token
            $this->validateCsrfToken($request, 'admin.order.done');

            try {
                /** @var Order $order */
                $order = $messageBusHandler->handleCommand(
                    new DoneBookOrderCommand(
                        orderId: (int)$order->getId(),
                    )
                );

                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans(
                        'ORDER_ENTITY.MESSAGE.DONE.SUCCESS',
                        [
                            '%order%' => $order->getId(),
                        ],
                        'SonataAdminBundle'
                    )
                );

                // redirect to list
                return $this->redirectToRoute('admin_order_list');
            } catch (Throwable $e) {
                $errorMessage = $e->getPrevious()?->getMessage() ?? $e->getMessage();
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans($errorMessage, [], $this->admin->getTranslationDomain())
                );
            }
        }

        return $this->renderWithExtraParams('admin/order/done.html.twig', [
            'object'     => $order,
            'order'      => $order,
            'action'     => 'done',
            'csrf_token' => $this->getCsrfToken('admin.order.done'),
        ]);
    }

}
