<?php

namespace Application\Game;

use Silex\Application;
use Application\Entity\UserBadgeEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Game
{
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Check if the user has earned any new badges
     *
     * @return void
     */
    public function badgesCheck()
    {
        $app = $this->app;

        $userExperiencePoints = $app['user']->getExperiencePoints();
        $userBadgesCollection = $app['user']->getUserBadges();
        $userBadges = array();
        $allBadges = Badges::getAllWithData();

        if (!empty($userBadgesCollection)) {
            foreach ($userBadgesCollection as $userBadge) {
                $userBadges[] = $userBadge->getBadge();
            }
        }

        foreach ($allBadges as $badge) {
            if ($badge->getMinimumExperiencePoints() === -1) {
                continue;
            }

            $badgeKey = $badge->getKey();

            if (
                !in_array($badgeKey, $userBadges) &&
                $userExperiencePoints > $badge->getMinimumExperiencePoints()
            ) {
                $userBadgeEntity = new UserBadgeEntity();
                $userBadgeEntity
                    ->setUser($app['user'])
                    ->setBadge($badgeKey)
                ;

                $app['orm.em']->persist($userBadgeEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'You have just earned the :badge: badge.',
                        array(
                            ':badge:' => $badge->getName(),
                        )
                    )
                );
            }
        }
    }
}
