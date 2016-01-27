<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/*========== Index ==========*/
$app->mount(
    '/',
    new Application\ControllerProvider\IndexControllerProvider()
);

/*========== API ==========*/
$app->mount(
    '/api',
    new Application\ControllerProvider\ApiControllerProvider()
);

/*========== Game ==========*/
$app->mount(
    '/game',
    new Application\ControllerProvider\GameControllerProvider()
);

/*========== Members Area ==========*/
$app->mount(
    '/members-area',
    new Application\ControllerProvider\MembersAreaControllerProvider()
);

/******** My ********/
$app->mount(
    '/members-area/my',
    new Application\ControllerProvider\MembersArea\MyControllerProvider()
);

/******** Users ********/
$app->mount(
    '/members-area/users',
    new Application\ControllerProvider\MembersArea\UsersControllerProvider()
);

/******** Posts ********/
$app->mount(
    '/members-area/posts',
    new Application\ControllerProvider\MembersArea\PostsControllerProvider()
);

/******** Countries ********/
$app->mount(
    '/members-area/countries',
    new Application\ControllerProvider\MembersArea\CountriesControllerProvider()
);

/******** Towns ********/
$app->mount(
    '/members-area/towns',
    new Application\ControllerProvider\MembersArea\TownsControllerProvider()
);

/******** Resources ********/
$app->mount(
    '/members-area/resources',
    new Application\ControllerProvider\MembersArea\ResourcesControllerProvider()
);

/******** Buildings ********/
$app->mount(
    '/members-area/buildings',
    new Application\ControllerProvider\MembersArea\BuildingsControllerProvider()
);

/******** Units ********/
$app->mount(
    '/members-area/units',
    new Application\ControllerProvider\MembersArea\UnitsControllerProvider()
);

/******** Items ********/
$app->mount(
    '/members-area/items',
    new Application\ControllerProvider\MembersArea\ItemsControllerProvider()
);

/******** Skills ********/
$app->mount(
    '/members-area/skills',
    new Application\ControllerProvider\MembersArea\SkillsControllerProvider()
);

/******** Statistics ********/
$app->mount(
    '/members-area/statistics',
    new Application\ControllerProvider\MembersArea\StatisticsControllerProvider()
);

/******** Settings ********/
$app->mount(
    '/members-area/settings',
    new Application\ControllerProvider\MembersArea\SettingsControllerProvider()
);

/*** Set Locale ***/
$app->match('/set-locale/{locale}', function ($locale) use ($app) {
    $cookie = new Cookie(
        'locale',
        $locale,
        new \DateTime('now + 1 year')
    );

    $response = Response::create(null, 302, array(
        'Location' => isset($_SERVER['HTTP_REFERER'])
            ? $_SERVER['HTTP_REFERER']
            : $app['baseUrl'],
    ));

    $response->headers->setCookie($cookie);

    return $response;
})
->bind('set-locale')
->assert('locale', implode('|', array_keys($app['locales'])));

/***** Errors *****/
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $app['application.mailer']
        ->swiftMessageInitializeAndSend(array(
            'subject' => $app['name'].' - '.$app['translator']->trans('An error occured'),
            'to' => array($app['email'] => $app['emailName']),
            'body' => 'emails/error.html.twig',
            'templateData' => array(
                'e' => $e,
                'code' => $code,
            ),
        ))
    ;

    // 404.html, or 40x.html, or 4xx.html, or default.html
    $templates = array(
        'contents/errors/'.$code.'.html.twig',
        'contents/errors/'.substr($code, 0, 2).'x.html.twig',
        'contents/errors/'.substr($code, 0, 1).'xx.html.twig',
        'contents/errors/default.html.twig',
    );

    return new Response(
        $app['twig']->resolveTemplate($templates)->render(
            array(
                'code' => $code,
            )
        ),
        $code
    );
});
