<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Message Entity
 *
 * @ORM\Table(name="user_messages")
 * @ORM\Entity(repositoryClass="Application\Repository\UserMessageRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserMessageEntity
{
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
     * @ORM\Column(name="subject", type="string", length=255)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

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
     * Who owns this message?
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="userMessages")
     */
    protected $user;

    /**
     * Who sent the message?
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserEntity", inversedBy="userMessagesSent")
     */
    protected $userFrom;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\UserMessageEntity", inversedBy="childUserMessages")
     * @ORM\JoinColumn(name="parent_user_message_id", referencedColumnName="id")
     */
    protected $parentUserMessage;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserMessageEntity", mappedBy="parentUserMessage")
     */
    protected $childUserMessages;

    /*************** Methods ***************/
    /**
     * Contructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->childUserMessages = new ArrayCollection();
    }

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
     * @return UserMessageEntity
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /*** Subject ***/
    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $subject
     *
     * @return UserMessageEntity
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /*** Content ***/
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     *
     * @return UserMessageEntity
     */
    public function setContent($content)
    {
        $this->content = $content;

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
     * @return UserMessageEntity
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
     * @return UserMessageEntity
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
     * @return UserMessageEntity
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
     * @return UserMessageEntity
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
     * @return UserMessageEntity
     */
    public function setUser(UserEntity $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /*** User from ***/
    /**
     * @return UserEntity
     */
    public function getUserFrom()
    {
        return $this->userFrom;
    }

    /**
     * @param UserEntity $userFrom
     *
     * @return UserMessageEntity
     */
    public function setUserFrom(UserEntity $userFrom = null)
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    /*** Parent User Message ***/
    /**
     * @return UserMessageEntity
     */
    public function getParentUserMessage()
    {
        return $this->parentUserMessage;
    }

    /**
     * @param UserMessageEntity $parentUserMessage
     *
     * @return UserMessageEntity
     */
    public function setParentUserMessage(UserMessageEntity $parentUserMessage = null)
    {
        $this->parentUserMessage = $parentUserMessage;

        return $this;
    }

    /*** Child User Messages ***/
    /**
     * @return UserMessageEntity
     */
    public function getChildUserMessages()
    {
        return $this->childUserMessages;
    }

    /**
     * @param UserMessageEntity $childUserMessage
     *
     * @return UserMessageEntity
     */
    public function setChildUserMessages($childUserMessages)
    {
        $this->childUserMessages = $childUserMessages;

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
            'subject' => $this->getSubject(),
            'content' => $this->getContent(),
            'time_seen' => $this->getTimeSeen()
                ? $this->getTimeSeen()->format(DATE_ATOM)
                : null,
            'time_acknowledged' => $this->getTimeAcknowledged()
                ? $this->getTimeAcknowledged()->format(DATE_ATOM)
                : null,
            'time_created' => $this->getTimeCreated()->format(DATE_ATOM),
            'user_from' => $this->getUserFrom()->toArray(),
            'user_to' => $this->getUser()->toArray(),
        );
    }

    /**
     * Magic Method - To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getSubject();
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
