<?php

namespace Application\Controller\MembersArea;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\Form\Type\SkillType;
use Application\Entity\SkillEntity;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class SkillsController
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

        if (
            !$app['security']->isGranted('ROLE_SKILLS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $limitPerPage = $request->query->get('limit_per_page', 20);
        $currentPage = $request->query->get('page');

        $skillResults = $app['orm.em']
            ->createQueryBuilder()
            ->select('s')
            ->from('Application\Entity\SkillEntity', 's')
        ;

        $pagination = $app['paginator']->paginate(
            $skillResults,
            $currentPage,
            $limitPerPage,
            array(
                'route' => 'members-area.skills',
                'defaultSortFieldName' => 's.timeCreated',
                'defaultSortDirection' => 'desc',
                'searchFields' => array(
                    's.name',
                    's.slug',
                    's.description',
                ),
            )
        );

        $data['pagination'] = $pagination;

        return new Response(
            $app['twig']->render(
                'contents/members-area/skills/index.html.twig',
                $data
            )
        );
    }

    /**
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function newAction(Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_SKILLS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $form = $app['form.factory']->create(
            new SkillType(),
            new SkillEntity()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $skillEntity = $form->getData();

                $app['orm.em']->persist($skillEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.skills.new.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.skills.edit',
                        array(
                            'id' => $skillEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/members-area/skills/new.html.twig',
                $data
            )
        );
    }

    /**
     * @param $id
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function editAction($id, Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_SKILLS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $skill = $app['orm.em']->find('Application\Entity\SkillEntity', $id);

        if (!$skill) {
            $app->abort(404);
        }

        $form = $app['form.factory']->create(
            new SkillType(),
            $skill
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $skillEntity = $form->getData();

                $app['orm.em']->persist($skillEntity);
                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.skills.edit.successText'
                    )
                );

                return $app->redirect(
                    $app['url_generator']->generate(
                        'members-area.skills.edit',
                        array(
                            'id' => $skillEntity->getId(),
                        )
                    )
                );
            }
        }

        $data['form'] = $form->createView();
        $data['skill'] = $skill;

        return new Response(
            $app['twig']->render(
                'contents/members-area/skills/edit.html.twig',
                $data
            )
        );
    }

    /**
     * @param $id
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function removeAction($id, Request $request, Application $app)
    {
        $data = array();

        if (
            !$app['security']->isGranted('ROLE_SKILLS_EDITOR') &&
            !$app['security']->isGranted('ROLE_ADMIN')
        ) {
            $app->abort(403);
        }

        $skills = array();
        $ids = $request->query->get('ids', false);
        $idsExploded = explode(',', $ids);
        foreach ($idsExploded as $singleId) {
            $singleEntity = $app['orm.em']->find(
                'Application\Entity\SkillEntity',
                $singleId
            );

            if ($singleEntity) {
                $skills[] = $singleEntity;
            }
        }

        $skill = $app['orm.em']->find('Application\Entity\SkillEntity', $id);

        if (
            (
                !$skill &&
                $ids === false
            ) ||
            (
                empty($skills) &&
                $ids !== false
            )
        ) {
            $app->abort(404);
        }

        $confirmAction = $app['request']->query->has('action') &&
            $app['request']->query->get('action') == 'confirm'
        ;

        if ($confirmAction) {
            try {
                if (!empty($skills)) {
                    foreach ($skills as $skill) {
                        $app['orm.em']->remove($skill);
                    }
                } else {
                    $app['orm.em']->remove($skill);
                }

                $app['orm.em']->flush();

                $app['flashbag']->add(
                    'success',
                    $app['translator']->trans(
                        'members-area.skills.remove.successText'
                    )
                );
            } catch (\Exception $e) {
                $app['flashbag']->add(
                    'danger',
                    $app['translator']->trans(
                        $e->getMessage()
                    )
                );
            }

            return $app->redirect(
                $app['url_generator']->generate(
                    'members-area.skills'
                )
            );
        }

        $data['skill'] = $skill;
        $data['skills'] = $skills;
        $data['ids'] = $ids;

        return new Response(
            $app['twig']->render(
                'contents/members-area/skills/remove.html.twig',
                $data
            )
        );
    }
}
