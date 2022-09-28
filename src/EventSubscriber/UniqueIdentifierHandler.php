<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Contracts\Entity\UserInterface;
use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\WishList\GetWishListQuery;
use App\Entity\WishList\WishList;
use App\Repository\WishList\WishListRepository;
use App\Service\MessageBusHandler;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Firewall\LogoutListener;
use Symfony\Component\Uid\UuidV4;
use function str_contains;

class UniqueIdentifierHandler //implements EventSubscriberInterface
{
//    public const UID_NAME = 'UID';
//
//    public function __construct(
//        private Security $security,
//        private MessageBusHandler $messageBusHandler,
//        private WishListRepository $wishListRepository,
//    ) {
//    }
//
//    #[NoReturn]
//    final public function onKernelRequest(RequestEvent $event): void
//    {
//        $request = $event->getRequest();
//
//        if ($request->attributes->has('_sonata_admin') ||
//            'sonata_admin_dashboard' === $request->attributes->get('_route') ||
//            str_contains($request->attributes->get('_route', ''), 'sonata_admin')
//        ) {
//            return;
//        }
//
//        $uid = UuidV4::isValid($request->cookies->get(self::UID_NAME, ''))
//            ? $request->cookies->get(self::UID_NAME)
//            : (new UuidV4())->toRfc4122();
//
//        // cookie not found for some reason
//        // and user logged in
//        // change the UID to the latest user's wish list
//        if (!$request->cookies->get(self::UID_NAME) &&
//            ($user = $this->resolveUser()) &&
//            $latestWishList = $this->resolveLatestUserWishListByUser($user->getId())
//        ) {
//            $uid = $latestWishList->getUid()->toRfc4122();
//        }
//
//        $wishList = $this->resolveDefaultWishList(UuidV4::fromString($uid), $request->getLocale());
//        $request->attributes->set(self::UID_NAME, $wishList->getUid()->toRfc4122());
//    }
//
//    #[NoReturn]
//    final public function onKernelResponse(ResponseEvent $event): void
//    {
//        $request = $event->getRequest();
//
//        if ($request->attributes->has('_sonata_admin') ||
//            'sonata_admin_dashboard' === $request->attributes->get('_route') ||
//            str_contains($request->attributes->get('_route', ''), 'sonata_admin')
//        ) {
//            return;
//        }
//
//        /**
//         * prolong uid
//         * only wen the request contains uid
//         * the request could do not contain the uid on the lowers request priorities,
//         * e.g.
//         *
//         * @see LogoutListener::getPriority() the default priority is "-127"
//         **/
//        if ($request->attributes->has(self::UID_NAME) &&
//            UuidV4::isValid($request->attributes->get(self::UID_NAME, ''))
//        ) {
//            $uid = $request->attributes->get(self::UID_NAME);
//            $event->getResponse()->headers->setCookie(
//                Cookie::create(
//                    name: self::UID_NAME,
//                    value: UuidV4::fromString($uid)->toRfc4122(),
//                    expire: new Carbon('+10 years')
//                )
//            );
//        }
//    }

//    public static function getSubscribedEvents(): array
//    {
//        return [
//            KernelEvents::REQUEST  => 'onKernelRequest',
//            KernelEvents::RESPONSE => 'onKernelResponse',
//        ];
//    }

//    private function resolveDefaultWishList(UuidV4 $uid, ?string $locale): WishList
//    {
//        return $this
//            ->messageBusHandler
//            ->handleQuery(
//                new GetWishListQuery(
//                    uid: $uid,
//                    id: null,
//                    locale: $locale,
//                    format: NormalizerFlags::FORMAT_ENTITY,
//                )
//            );
//    }
//
//    private function resolveUser(): ?UserInterface
//    {
//        $user = $this->security->getUser();
//
//        return $user instanceof UserInterface
//            ? $user
//            : null;
//    }
//
//    private function resolveLatestUserWishListByUser(int $userId): ?WishList
//    {
//        return $this->wishListRepository->getLatestByUserIdWithoutRelation($userId);
//    }
}
