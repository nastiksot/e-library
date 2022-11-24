<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Contracts\Dictionary\ReadingType;
use App\CQ\Command\Stock\ReserveAddStockCommand;
use App\CQ\Command\Stock\ReserveDoneStockCommand;
use App\Entity\Book\Book;
use App\Entity\Reading;
use App\Entity\User\User;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReadingFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const COUNT_READING = 10;

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
            // create reading for super admin
            $this->createActualReading($i, 'super-admin', $superAdmin);
            $this->createExpiredReading($i, 'super-admin', $superAdmin);

            // create reading for admin
            $this->createActualReading($i, 'admin', $admin);
            $this->createExpiredReading($i, 'admin', $admin);

            // create reading for librarian
            $this->createActualReading($i, 'librarian', $librarian);
            $this->createExpiredReading($i, 'librarian', $librarian);
        }

        // create orders to reader users
        for ($i = 1; $i <= self::COUNT_READING; $i++) {

            /** @var User $reader */
            $reader = $this->getReference('reader-' . random_int(1, (int)ReaderFixture::$counter));

            // create order
            $this->createActualReading($i, 'reader', $reader);
            $this->createExpiredReading($i, 'reader', $reader);
        }
    }

    private function createActualReading(int $index, string $prefix, User $user): void
    {
        /** @var Book $book */
        $book = $this->getReference('book-' . random_int(1, (int)BookFixture::$counter));

        $reading = (new Reading())
            ->setBook($book)
            ->setUser($user)
            ->setQuantity(1)
            ->setReadingType(ReadingType::READING_HALL())
            ->setStartAt(Carbon::today())
            ->setEndAt(Carbon::today()->addDays(5));

        $this->em->getUnitOfWork()->persist($reading);
        $this->em->getUnitOfWork()->commit($reading);

        $this->messageBusHandler->handleCommand(
            new ReserveAddStockCommand((int)$reading->getBook()?->getId(), $reading->getQuantity())
        );

        $this->messageBusHandler->handleCommand(
            new ReserveDoneStockCommand((int)$reading->getBook()?->getId(), $reading->getQuantity())
        );


        $this->addReference('reading-' . $prefix . '-actual-' . $index, $reading);
    }

    private function createExpiredReading(int $index, string $prefix, User $user): void
    {
        /** @var Book $book */
        $book = $this->getReference('book-' . random_int(1, (int)BookFixture::$counter));

        $reading = (new Reading())
            ->setBook($book)
            ->setUser($user)
            ->setQuantity(1)
            ->setReadingType(ReadingType::READING_HALL())
            ->setStartAt(Carbon::today()->subMonths(1))
            ->setEndAt(Carbon::today()->subDays(5));

        $this->em->getUnitOfWork()->persist($reading);
        $this->em->getUnitOfWork()->commit($reading);

        $this->messageBusHandler->handleCommand(
            new ReserveAddStockCommand((int)$reading->getBook()?->getId(), $reading->getQuantity())
        );

        $this->messageBusHandler->handleCommand(
            new ReserveDoneStockCommand((int)$reading->getBook()?->getId(), $reading->getQuantity())
        );

        $this->addReference('reading-' . $prefix . '-expired-' . $index, $reading);
    }
}
