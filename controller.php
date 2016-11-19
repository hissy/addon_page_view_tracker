<?php
namespace Concrete\Package\PageViewTracker;

use Concrete\Core\Package\Package;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Page\Page;
use Database;
use Events;
use PageViewTracker\Entity\Hit;

class Controller extends Package
{
    /**
     * @var string Package handle.
     */
    protected $pkgHandle = 'page_view_tracker';

    /**
     * @var string Required concrete5 version.
     */
    protected $appVersionRequired = '5.7.5';

    /**
     * @var string Package version.
     */
    protected $pkgVersion = '0.2.1';

    /**
     * @var boolean Remove \Src from package namespace.
     */
    protected $pkgAutoloaderMapCoreExtensions = true;

    /**
     * @var array register package autoloader
     */
    protected $pkgAutoloaderRegistries = array(
        'src/PageViewTracker' => 'PageViewTracker',
    );

    /**
     * Returns the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t('Track page view stats into the database and get count of the current page.');
    }

    /**
     * Returns the installed package version.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t('Page View Tracker');
    }

    public function install()
    {
        $pkg = parent::install();
        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/config/install.xml');
    }

    public function uninstall()
    {
        $dbm = $this->getDatabaseStructureManager();

        if ($dbm->hasEntities()) {
            $dbm->uninstallDatabase();
        }

        parent::uninstall();
    }

    public function on_start()
    {
        $db = Database::connection();
        $em = $db->getEntityManager();
        Events::addListener('on_page_view', function ($event) use ($em) {
            /** @var Page $c */
            $c = $event->getPageObject();
            if (!$c->isAdminArea()) {
                $hit = new Hit($event->getPageObject(), $event->getUserObject());
                $em->persist($hit);
                $em->flush();
            }
        });
    }
}
