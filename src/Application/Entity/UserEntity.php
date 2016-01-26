<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Application\Game\Experience;

/**
 * User Entity
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UserEntity implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * What is the locale for this user?
     *
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=8, nullable=true)
     */
    protected $locale = 'en_US';

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=64, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, unique=true)
     */
    protected $email;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable=true)
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * Used only when saving the user.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * Used only when saving a new password.
     *
     * @var string
     */
    protected $oldPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * Used for emails & co.
     *
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    protected $token;

    /**
     * Used for authentification & co.
     *
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    protected $accessToken;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    protected $locked = false;

    /**
     * @var string
     *
     * @ORM\Column(name="reset_password_code", type="string", length=255, nullable=true, unique=true)
     */
    protected $resetPasswordCode;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_code", type="string", length=255, nullable=true, unique=true)
     */
    protected $activationCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="experience_points", type="integer")
     */
    protected $experiencePoints = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="health_points", type="integer")
     */
    protected $healthPoints = 100;

    /**
     * @var integer
     *
     * @ORM\Column(name="health_points_left", type="integer")
     */
    protected $healthPointsLeft = 100;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_last_active", type="datetime", nullable=true)
     */
    protected $timeLastActive;

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

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\ProfileEntity", mappedBy="user", cascade={"all"})
     **/
    protected $profile;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\PostEntity", mappedBy="user", cascade={"all"})
     */
    protected $posts;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\TownEntity", mappedBy="user", cascade={"all"})
     */
    protected $towns;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\CountryEntity", mappedBy="user", cascade={"all"})
     */
    protected $countries;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserBadgeEntity", mappedBy="user", cascade={"all"})
     */
    protected $userBadges;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserSkillEntity", mappedBy="user", cascade={"all"})
     */
    protected $userSkills;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserNotificationEntity", mappedBy="user", cascade={"all"})
     */
    protected $userNotifications;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserMessageEntity", mappedBy="user", cascade={"all"})
     */
    protected $userMessages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserMessageEntity", mappedBy="userFrom", cascade={"all"})
     */
    protected $userMessagesSent;

    /**
     * Otherwise known as: userExpired / accountExpired
     *
     * @var boolean
     */
    protected $expired = false;

    /**
     * @var boolean
     */
    protected $credentialsExpired = false;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->setSalt(
            md5(uniqid(null, true))
        );

        $this->setToken(
            md5(uniqid(null, true))
        );

        $this->setAccessToken(
            md5(uniqid(null, true))
        );

        $this->setActivationCode(
            md5(uniqid(null, true))
        );

        $this->setResetPasswordCode(
            md5(uniqid(null, true))
        );

        $this->posts = new ArrayCollection();
        $this->towns = new ArrayCollection();
        $this->userBadges = new ArrayCollection();
        $this->userNotifications = new ArrayCollection();
        $this->userMessages = new ArrayCollection();
        $this->userMessagesSent = new ArrayCollection();
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
     * @param integer $id
     *
     * @return UserEntity
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /*** Locale ***/
    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return UserEntity
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /*** Username ***/
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return UserEntity
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /*** Email ***/
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return UserEntity
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /*** Password ***/
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return UserEntity
     */
    public function setPassword($password)
    {
        if ($password) {
            $this->password = $password;
        }

        return $this;
    }

    /*** Plain password ***/
    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string         $plainPassword
     * @param EncoderFactory $encoderFactory
     *
     * @return UserEntity
     */
    public function setPlainPassword($plainPassword, EncoderFactory $encoderFactory = null)
    {
        $this->plainPassword = $plainPassword;

        if ($encoderFactory) {
            $encoder = $encoderFactory->getEncoder($this);

            $password = $encoder->encodePassword(
                $this->getPlainPassword(),
                $this->getSalt()
            );

            $this->setPassword($password);
        }

        return $this;
    }

    /*** Old password ***/
    /**
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     *
     * @return UserEntity
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /*** Salt ***/
    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     *
     * @return UserEntity
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /*** Token ***/
    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return UserEntity
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /*** Access Token ***/
    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return UserEntity
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /*** Enabled ***/
    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return UserEntity
     */
    public function enable()
    {
        $this->setEnabled(true);

        return $this;
    }

    /**
     * @return UserEntity
     */
    public function disable()
    {
        $this->setEnabled(false);

        return $this;
    }

    /*** Locked ***/
    /**
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @return boolean
     */
    public function isLocked()
    {
        return $this->getLocked();
    }

    /**
     * @param boolean $locked
     *
     * @return UserEntity
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return UserEntity
     */
    public function lock()
    {
        $this->setLocked(true);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAccountNonLocked()
    {
        return !$this->isLocked();
    }

    /*** Reset password code ***/
    /**
     * @return string
     */
    public function getResetPasswordCode()
    {
        return $this->resetPasswordCode;
    }

    /**
     * @param string $resetPasswordCode
     *
     * @return UserEntity
     */
    public function setResetPasswordCode($resetPasswordCode)
    {
        $this->resetPasswordCode = $resetPasswordCode;

        return $this;
    }

    /*** Activation code ***/
    /**
     * @return string
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param string $activationCode
     *
     * @return UserEntity
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /*** Experience points ***/
    /**
     * @return integer
     */
    public function getExperiencePoints()
    {
        return $this->experiencePoints;
    }

    /**
     * @param integer $experiencePoints
     *
     * @return UserEntity
     */
    public function setExperiencePoints($experiencePoints)
    {
        $this->experiencePoints = $experiencePoints;

        return $this;
    }

    /**
     * @param integer $experiencePoints
     *
     * @return UserEntity
     */
    public function addExperiencePoints($experiencePoints)
    {
        $this->experiencePoints = $this->getExperiencePoints() + abs($experiencePoints);

        return $this;
    }

    /*** Experience Level ***/
    /**
     * @return integer
     */
    public function getExperienceLevel()
    {
        return Experience::getLevelByPoints(
            $this->getExperiencePoints()
        );
    }

    /**
     * @return integer
     */
    public function getCurrentExperienceLevelMinimumPoints()
    {
        return Experience::getPointsByLevel(
            $this->getExperienceLevel()
        );
    }

    /**
     * @return integer
     */
    public function getNextExperienceLevelMinimumPoints()
    {
        return Experience::getPointsByLevel(
            $this->getExperienceLevel()+1
        );
    }

    /*** Next experience level percentage ***/
    /**
     * @return integer
     */
    public function getNextExperienceLevelPercentage()
    {
        $now = $this->getExperiencePoints();
        $start = $this->getCurrentExperienceLevelMinimumPoints();
        $end = $this->getNextExperienceLevelMinimumPoints();

        if ($now === 0) {
            return 0;
        }

        return ($now - $start) / ($end - $start) * 100;
    }

    /*** Health points ***/
    /**
     * @return integer
     */
    public function getHealthPoints()
    {
        return $this->healthPoints;
    }

    /**
     * @param integer $healthPoints
     *
     * @return UserEntity
     */
    public function setHealthPoints($healthPoints)
    {
        $this->healthPoints = $healthPoints;

        return $this;
    }

    /*** Health points left ***/
    /**
     * @return integer
     */
    public function getHealthPointsLeft()
    {
        return $this->healthPointsLeft;
    }

    /**
     * @param integer $healthPointsLeft
     *
     * @return UserEntity
     */
    public function setHealthPointsLeft($healthPointsLeft)
    {
        $this->healthPointsLeft = $healthPointsLeft;

        return $this;
    }

    /*** Health points percentage ***/
    /**
     * @return integer
     */
    public function getHealthPointsPercentage()
    {
        $total = $this->getHealthPoints();
        $left = $this->getHealthPointsLeft();

        if ($total == 0) {
            return 100;
        } elseif ($left == 0) {
            return 0;
        }

        return ceil(($left / $total) * 100);
    }

    /**
     * @return string
     */
    public function getHealthPointsColorType()
    {
        $percentage = $this->getHealthPointsPercentage();
        $type = 'success';

        if ($percentage <= 20) {
            $type = 'danger';
        } elseif ($percentage <= 40) {
            $type = 'warning';
        }

        return $type;
    }

    /*** Time last active ***/
    /**
     * @return \DateTime
     */
    public function getTimeLastActive()
    {
        return $this->timeLastActive;
    }

    /**
     * @param $timeLastActive
     *
     * @return UserEntity
     */
    public function setTimeLastActive(\Datetime $timeLastActive = null)
    {
        $this->timeLastActive = $timeLastActive;

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
     * @return UserEntity
     */
    public function setTimeCreated(\Datetime $timeCreated)
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
     * @return UserEntity
     */
    public function setTimeUpdated(\DateTime $timeUpdated)
    {
        $this->timeUpdated = $timeUpdated;

        return $this;
    }

    /***** Avatar image url *****/
    /**
     * @return string
     */
    public function getAvatarImageUrl($baseUrl)
    {
        $imageUrl = $this->getProfile()->getImageUrl();
        if ($imageUrl) {
            return $imageUrl;
        }

        // To-Do: Throw a warning or something, when no $baseUrl is given

        $avatarImage = $this->getProfile()->getAvatarImage();
        if ($avatarImage) {
            return $baseUrl.'/assets/images/avatars/'.$avatarImage;
        }

        return $baseUrl.$this->getProfile()->getPlaceholderImageUri();
    }

    /*** Expired ***/
    /**
     * @return boolean
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return $this->getExpired();
    }

    /**
     * @return boolean
     */
    public function isAccountNonExpired()
    {
        return !$this->getExpired();
    }

    /*** Credentials expired ***/
    /**
     * @return boolean
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }

    /**
     * @return boolean
     */
    public function isCredentialsExpired()
    {
        return $this->getCredentialsExpired();
    }

    /**
     * @return boolean
     */
    public function isCredentialsNonExpired()
    {
        return !$this->getExpired();
    }

    /*** Roles ***/
    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = is_array($this->roles)
            ? $this->roles
            : array()
        ;
        $roles[] = 'ROLE_USER';

        return (array) array_unique($roles, SORT_REGULAR);
    }

    /**
     * @param array $roles
     *
     * @return UserEntity
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(
            $role,
            $this->getRoles()
        );
    }

    /*** Profile ***/
    /**
     * @return ProfileEntity
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param ProfileEntity $profile
     *
     * @return UserEntity
     */
    public function setProfile(ProfileEntity $profile)
    {
        $this->profile = $profile;

        $this->getProfile()->setUser($this);

        return $this;
    }

    /*** Posts ***/
    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->posts->toArray();
    }

    /**
     * @param ArrayCollection $posts
     *
     * @return UserEntity
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /*** Towns ***/
    /**
     * @return array
     */
    public function getTowns()
    {
        return $this->towns->toArray();
    }

    /**
     * @param ArrayCollection $towns
     *
     * @return UserEntity
     */
    public function setTowns($towns)
    {
        $this->towns = $towns;

        return $this;
    }

    /**
     * @param TownEntty $town
     *
     * @return boolean
     */
    public function hasTown(TownEntity $town)
    {
        return $this->towns->contains($town);
    }
    
    /**
     * @return integer
     */
    public function getTownsLimit()
    {
        return 1;
    }
    
    /**
     * @return boolean
     */
    public function canCreateNewTown()
    {
        if ($this->hasRole('ROLE_ADMIN')) {
            return true;
        }
        
        return count($this->getTowns()) < $this->getTownsLimit();
    }

    /*** Town buildings ***/
    /**
     * @return array
     */
    public function getTownBuildings()
    {
        $towns = $this->getTowns();
        $townBuildings = array();

        if (!empty($towns)) {
            foreach ($towns as $town) {
                $townTownBuildings = $town->getTownBuildings();

                if (!empty($townTownBuildings)) {
                    foreach ($townTownBuildings as $townTownBuilding) {
                        $townBuildings[] = $townTownBuilding;
                    }
                }
            }
        }

        return $townBuildings;
    }

    /**
     * @param TownBuildingEntty $townBuilding
     *
     * @return boolean
     */
    public function hasTownBuilding(TownBuildingEntity $townBuilding)
    {
        return $this->hasTown(
            $townBuilding->getTown()
        );
    }

    /*** Countries ***/
    /**
     * @return array
     */
    public function getCountries()
    {
        return $this->countries->toArray();
    }

    /**
     * @param ArrayCollection $countries
     *
     * @return UserEntity
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;

        return $this;
    }
    
    /**
     * @return integer
     */
    public function getCountriesLimit()
    {
        return 1;
    }
    
    /**
     * @return boolean
     */
    public function canCreateNewCountry()
    {
        if ($this->hasRole('ROLE_ADMIN')) {
            return true;
        }
        
        return count($this->getCountries()) < $this->getCountriesLimit();
    }

    /*** User badges ***/
    /**
     * @return array
     */
    public function getUserBadges()
    {
        return $this->userBadges->toArray();
    }

    /**
     * @param ArrayCollection $userBadges
     *
     * @return UserEntity
     */
    public function setUserBadges($userBadges)
    {
        $this->userBadges = $userBadges;

        return $this;
    }

    /*** User skills ***/
    /**
     * @return array
     */
    public function getUserSkills()
    {
        return $this->userSkills->toArray();
    }

    /**
     * @param ArrayCollection $userSkills
     *
     * @return UserEntity
     */
    public function setUserSkills($userSkills)
    {
        $this->userSkills = $userSkills;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserSkillPointsByKey($key)
    {
        $userSkills = $this->getUserSkills();

        if (!empty($userSkills)) {
            foreach ($userSkills as $userSkill) {
                if ($userSkill->getSkill() == $key) {
                    return $userSkill->getPoints();
                }
            }
        }

        return 0;
    }

    /*** User notifications ***/
    /**
     * @return array
     */
    public function getUserNotifications()
    {
        return $this->userNotifications->toArray();
    }

    /**
     * @param $userNotifications
     *
     * @return UserEntity
     */
    public function setUserNotifications($userNotifications)
    {
        $this->userNotifications = $userNotifications;

        return $this;
    }

    /**
     * @return array
     */
    public function getUnreadUserNotifications()
    {
        $criteria = Criteria::create()
            ->where(
                Criteria::expr()->eq(
                    'timeAcknowledged',
                    null
                )
            )
        ;

        return $this
            ->userNotifications
            ->matching($criteria)
            ->toArray()
        ;
    }

    /*** User messages ***/
    /**
     * @return array
     */
    public function getUserMessages()
    {
        return $this->userMessages->toArray();
    }

    /**
     * @param $userMessages
     *
     * @return UserEntity
     */
    public function setUserMessages($userMessages)
    {
        $this->userMessages = $userMessages;

        return $this;
    }

    /**
     * @return array
     */
    public function getUnreadUserMessages()
    {
        $criteria = Criteria::create()
            ->where(
                Criteria::expr()->eq(
                    'timeAcknowledged',
                    null
                )
            )
        ;

        return $this
            ->userMessages
            ->matching($criteria)
            ->toArray()
        ;
    }

    /*** User messages sent ***/
    /**
     * @return array
     */
    public function getUserMessagesSent()
    {
        return $this->userMessagesSent->toArray();
    }

    /**
     * @param $userMessagesSent
     *
     * @return UserEntity
     */
    public function setUserMessagesSent($userMessagesSent)
    {
        $this->userMessagesSent = $userMessagesSent;

        return $this;
    }

    /**
     * @param AdvancedUserInterface $user
     *
     * @return boolean
     */
    public function isEqualTo(AdvancedUserInterface $user)
    {
        if (!($user instanceof AdvancedUserInterface)) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
        $this->setPlainPassword(null);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
        ));
    }

    /**
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
        ) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'first_name' => $this->getProfile()->getFirstName(),
            'last_name' => $this->getProfile()->getLastName(),
            'full_name' => $this->getProfile()->getFullName(),
            'time_created' => $this->getTimeCreated()->toArray(DATE_ATOM),
        );
    }

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
