<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User notification Entity
 *
 * @ORM\Table(name="user_notifications")
 * @ORM\Entity(repositoryClass="Application\Repository\UserNotificationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserNotificationEntity
{
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /*************** Variables ***************/
    /********** General Variables **********/
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=64, nullable=true)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", length=64, nullable=true)
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    protected $data;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=16, nullable=true)
     */
    protected $priority;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_seen", type="datetime", nullable=true)
     */
    protected $timeSeen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_acknowledged", type="datetime", nullable=true)
     */
    protected $timeAcknowledged;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_created", type="datetime")
     */
    protected $timeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_updated", type="datetime")
     */
    protected $timeUpdated;

    /***** Relationship Variables *****/
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="userNotifications")
     */
    protected $user;

    /**
     * Temporary.
     * Used when creating new notification.
     */
    protected $users;

    /*************** Methods ***************/
    /*** Id ***/
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return UserNotificationEntity
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /*** Type ***/
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     *
     * @return UserNotificationEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /*** Key ***/
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $key
     *
     * @return UserNotificationEntity
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /*** Message ***/
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     *
     * @return UserNotificationEntity
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /*** Priority ***/
    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $priority
     *
     * @return UserNotificationEntity
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /*** Data ***/
    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     *
     * @return UserNotificationEntity
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /*** Time seen ***/
    /**
     * @return \DateTime
     */
    public function getTimeSeen()
    {
        return $this->timeSeen;
    }

    /**
     * @param \DateTime $timeSeen
     *
     * @return UserNotificationEntity
     */
    public function setTimeSeen(\DateTime $timeSeen = null)
    {
        $this->timeSeen = $timeSeen;

        return $this;
    }

    /*** Time acknowledged ***/
    /**
     * @return \DateTime
     */
    public function getTimeAcknowledged()
    {
        return $this->timeAcknowledged;
    }

    /**
     * @param \DateTime $timeAcknowledged
     *
     * @return UserNotificationEntity
     */
    public function setTimeAcknowledged(\DateTime $timeAcknowledged = null)
    {
        $this->timeAcknowledged = $timeAcknowledged;

        return $this;
    }

    /*** Time created ***/
    /**
     * @return \DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * @param \DateTime $timeCreated
     *
     * @return UserNotificationEntity
     */
    public function setTimeCreated(\DateTime $timeCreated)
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    /*** Time updated ***/
    /**
     * @return \DateTime
     */
    public function getTimeUpdated()
    {
        return $this->timeUpdated;
    }

    /**
     * @param \DateTime $timeUpdated
     *
     * @return UserNotificationEntity
     */
    public function setTimeUpdated(\DateTime $timeUpdated)
    {
        $this->timeUpdated = $timeUpdated;

        return $this;
    }

    /*** User ***/
    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserEntity $user
     *
     * @return UserNotificationEntity
     */
    public function setUser(UserEntity $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /*** Users ***/
    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return UserNotificationEntity
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /********** Other Methods **********/
    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'priority' => $this->getPriority(),
            'type' => $this->getType(),
            'message' => $this->getMessage(),
            'time_seen' => $this->getTimeSeen()
                ? $this->getTimeSeen()->format(DATE_ATOM)
                : null,
            'time_acknowledged' => $this->getTimeAcknowledged()
                ? $this->getTimeAcknowledged()->format(DATE_ATOM)
                : null,
            'time_created' => $this->getTimeCreated()->format(DATE_ATOM),
        );
    }

    /**
     * Magic Method - To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    /**
     * @return array
     */
    public static function getPriorities()
    {
        return array(
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_URGENT => 'Urgent',
        );
    }

    /********** Callback Methods **********/
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setTimeUpdated(new \DateTime('now'));
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setTimeUpdated(new \DateTime('now'));
        $this->setTimeCreated(new \DateTime('now'));
    }
}
