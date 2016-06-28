<?php
namespace Concrete\Package\PageViewTracker\Block\PageViews;

use Concrete\Core\Block\BlockController;
use Page;
use Database;

class Controller extends BlockController
{
    protected $btDefaultSet = 'multimedia';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 3600; // an hour

    public function getBlockTypeDescription()
    {
        return t("A block to show page view count of the page.");
    }

    public function getBlockTypeName()
    {
        return t('Page Views');
    }

    public function view()
    {
        $c = Page::getCurrentPage();
        if (is_object($c) && !$c->isError()) {
            $db = Database::connection();
            $em = $db->getEntityManager();
            $qb = $em->createQueryBuilder();
            $count = $qb->select('COUNT(h.id) as pagehit')
                ->from('PageViewTracker\Entity\Hit', 'h')
                ->where('h.cID = :cID')
                ->setParameter('cID', $c->getCollectionID())
                ->getQuery()
                ->getOneOrNullResult();
            $this->set('pagehit', $count['pagehit']);
        }
    }
}
