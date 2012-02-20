<?php

namespace Knp\Component\Pager\Event\Subscriber\Paginate;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\Event\PaginationEvent;
use Knp\Component\Pager\Event\BeforeEvent;
use Knp\Component\Pager\Pagination\SlidingPagination;

class PaginationSubscriber implements EventSubscriberInterface
{
    public function pagination(PaginationEvent $event)
    {
        $event->setPagination(new SlidingPagination);
        $event->stopPropagation();
    }

    public function before(BeforeEvent $event)
    {
        $disp = $event->getEventDispatcher();
        // hook all standard subscribers
        $disp->addSubscriber(new ArraySubscriber);
        $disp->addSubscriber(new Doctrine\ORM\QueryBuilderSubscriber);
        $disp->addSubscriber(new Doctrine\ORM\QuerySubscriber);
        $disp->addSubscriber(new Doctrine\ODM\MongoDB\QueryBuilderSubscriber);
        $disp->addSubscriber(new Doctrine\ODM\MongoDB\QuerySubscriber);
        $disp->addSubscriber(new Doctrine\CollectionSubscriber);
        $disp->addSubscriber(new PropelQuerySubscriber);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.before' => array('before', 0),
            'knp_pager.pagination' => array('pagination', 0)
        );
    }
}
