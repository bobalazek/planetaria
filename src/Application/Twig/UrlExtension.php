<?php

namespace Application\Twig;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class UrlExtension extends \Twig_Extension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'application/date';
    }

    /**
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('url_decode', array($this, 'urlDecode')),
        );
    }

    /**
     * @return string
     */
    public function urlDecode($url)
    {
        return urldecode($url);
    }
}
