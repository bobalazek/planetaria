<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class ErrorsController
{
    /**
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Request $request, Application $app)
    {
        $data = array();

        if (!$app['security']->isGranted('ROLE_ADMIN')) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $errorResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('e')
            ->from('Application\Entity\ErrorEntity', 'e')
        ;

        $pagination = $app['paginator']->paginate(
            $errorResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.errors',
                'defaultSortFieldName' => 'e.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    'e.code',
                    'e.message',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/errors/index.html.twig',
                $data
            )
        );
    }
}
