<?php
namespace PageViewTracker\Entity;

use Page;
use User;

/**
 * Class Hit
 * @package PageViewTracker\Entity
 * @Entity
 * @Table(
 *     name="PageViewHits",
 *     indexes={
 *         @Index(name="cID", columns={"cID"}),
 *         @Index(name="uID", columns={"uID"})
 *     }
 * )
 * @HasLifecycleCallbacks
 */
class Hit
{
    /**
     * @var integer Hit ID.
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var integer Page ID.
     * @Column(type="integer")
     */
    private $cID;

    /**
     * @var integer User ID.
     * @Column(type="integer")
     */
    private $uID;

    /**
     * @var \DateTime created time.
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * Hit constructor.
     * @param Page $c
     * @param User $u
     */
    public function __construct(Page $c, User $u)
    {
        $this->cID = $c->getCollectionID();
        $this->uID = $u->getUserID();
    }

    /**
     * @return int
     */
    public function getCID()
    {
        return $this->cID;
    }

    /**
     * @param int $cID
     * @return Hit
     */
    public function setCID($cID)
    {
        $this->cID = $cID;
        return $this;
    }

    /**
     * @return int
     */
    public function getUID()
    {
        return $this->uID;
    }

    /**
     * @param int $uID
     * @return Hit
     */
    public function setUID($uID)
    {
        $this->uID = $uID;
        return $this;
    }

    /**
     * @PrePersist
     */
    public function prePersist()
    {
        $this->uID = ($this->uID) ? $this->uID : 0;
        $this->createdAt = new \DateTime();
    }
}