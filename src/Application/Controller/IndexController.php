<?php

namespace Application\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class IndexController
{
    /**
     * @param Application $app
     *
     * @return Response
     */
    public function indexAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/index.html.twig'
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function termsAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/pages/terms.html.twig'
            )
        );
    }

    /**
     * @param Application $app
     *
     * @return Response
     */
    public function privacyAction(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'contents/pages/privacy.html.twig'
            )
        );
    }

    /**
     * @param Request     $request
     * @param Application $app
     *
     * @return Response
     */
    public function contactUsAction(Request $request, Application $app)
    {
        $data = array();

        $formData = array();
        $form = $app['form.factory']->create(
            new \Application\Form\Type\ContactUsType()
        );

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $formData = $form->getData();

                $message = \Swift_Message::newInstance()
                    ->setSubject(
                        $app['name'].' - '.$app['translator']->trans('Contact us')
                    )
                    ->setFrom(
                        array(
                            $formData['email'] => $formData['name'],
                        )
                    )
                    ->setTo(
                        array(
                            $app['email'] => $app['emailName'],
                        )
                    )
                    ->setCc(
                        array(
                            $formData['email'] => $formData['name'],
                        )
                    )
                    ->setBody(
                        $app['mailer.css_to_inline_styles_converter'](
                            'emails/contact-us.html.twig',
                            array(
                                'app' => $app,
                                'formData' => $formData,
                            )
                        ),
                        'text/html'
                    )
                ;
                $app['mailer']->send($message);

                $data['success'] = true;
                $data['successMessage'] = $app['translator']->trans(
                    'contact-us.success'
                );
            }
        }

        $data['formData'] = $formData;
        $data['form'] = $form->createView();

        return new Response(
            $app['twig']->render(
                'contents/pages/contact-us.html.twig',
                $data
            )
        );
    }
}
