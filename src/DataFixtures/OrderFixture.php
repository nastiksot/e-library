<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Contracts\Dictionary\ReadingType;
use App\CQ\Command\Order\CancelOrderCommand;
use App\CQ\Command\Order\CreateOrderCommand;
use App\CQ\Command\Order\DoneOrderCommand;
use App\Entity\Book\Book;
use App\Entity\Order;
use App\Entity\User\User;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const COUNT_ORDERS = 10;

    public function getDependencies()
    {
        return [
            UserFixture::class,
            StockFixture::class,
            ReaderFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        /** @var User $superAdmin */
        $superAdmin = $this->getReference('super-admin');
        /** @var User $admin */
        $admin = $this->getReference('admin');

        /** @var User $librarian */
        $librarian = $this->getReference('librarian');

        // create orders to system users
        for ($i = 1; $i <= 5; $i++) {
            // create order for super admin
            $this->createOrderStatusOpen($i, 'super-admin', $superAdmin);
            $this->createOrderStatusCancel($i, 'super-admin', $superAdmin);
            $this->createOrderStatusDone($i, 'super-admin', $superAdmin);

            // create order for admin
            $this->createOrderStatusOpen($i, 'admin', $admin);
            $this->createOrderStatusCancel($i, 'admin', $admin);
            $this->createOrderStatusDone($i, 'admin', $admin);

            // create order for librarian
            $this->createOrderStatusOpen($i, 'librarian', $librarian);
            $this->createOrderStatusCancel($i, 'librarian', $librarian);
            $this->createOrderStatusDone($i, 'librarian', $librarian);
        }

        // create orders to reader users
        for ($i = 1; $i <= self::COUNT_ORDERS; $i++) {

            /** @var User $reader */
            $reader = $this->getReference('reader-' . random_int(1, (int)ReaderFixture::$counter));

            // create order
            $this->createOrderStatusOpen($i, 'reader', $reader);
        }
    }

    private function createOrderStatusOpen(int $index, string $prefix, User $user): void
    {
        /** @var Book $book */
        $book = $this->getReference('book-' . random_int(1, (int)BookFixture::$counter));

        /** @var Order $order */
        $order = $this->messageBusHandler->handleCommand(
            new CreateOrderCommand(
                bookId:      (int)$book->getId(),
                userId:      (int)$user->getId(),
                quantity:    (int)random_int(1, 5),
                readingType: (string)ReadingType::READING_HALL()->getValue(),
                startAt:     Carbon::today(),
                endAt:       Carbon::today()->addDays(5),
            )
        );

        $this->addReference('order-' . $prefix . '-open-' . $index, $order);
    }

    private function createOrderStatusCancel(int $index, string $prefix, User $user): void
    {
        /** @var Book $book */
        $book = $this->getReference('book-' . random_int(1, (int)BookFixture::$counter));

        /** @var Order $order */
        $order = $this->messageBusHandler->handleCommand(
            new CreateOrderCommand(
                bookId:      (int)$book->getId(),
                userId:      (int)$user->getId(),
                quantity:    (int)random_int(1, 5),
                readingType: (string)ReadingType::READING_HALL()->getValue(),
                startAt:     Carbon::today(),
                endAt:       Carbon::today()->addDays(5),
            )
        );

        /** @var Order $order */
        $order = $this->messageBusHandler->handleCommand(new CancelOrderCommand((int)$order->getId()));
        $this->addReference('order-' . $prefix . '-cancel-' . $index, $order);
    }

    private function createOrderStatusDone(int $index, string $prefix, User $user): void
    {
        /** @var Book $book */
        $book = $this->getReference('book-' . random_int(1, (int)BookFixture::$counter));

        /** @var Order $order */
        $order = $this->messageBusHandler->handleCommand(
            new CreateOrderCommand(
                bookId:      (int)$book->getId(),
                userId:      (int)$user->getId(),
                quantity:    (int)random_int(1, 5),
                readingType: (string)ReadingType::READING_HALL()->getValue(),
                startAt:     Carbon::today(),
                endAt:       Carbon::today()->addDays(5),
            )
        );

        /** @var Order $order */
        $order = $this->messageBusHandler->handleCommand(new DoneOrderCommand((int)$order->getId()));
        $this->addReference('order-' . $prefix . '-done-' . $index, $order);
    }

}
