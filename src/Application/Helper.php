<?php

namespace Application;

/**
 * @author Borut Balažek <bobalazek124@gmail.com>
 */
class Helper
{
    /**
     * @return boolean
     */
    public static function strpos_array($haystack, $needle, $offset = 0)
    {
        if (is_array($haystack)) {
            foreach ($haystack as $i => $haystackSingle) {
                $pos = Helper::strpos_array($haystackSingle, $needle);

                if ($pos !== false) {
                    return $i;
                }
            }

            return false;
        } else {
            return strpos($haystack, $needle, $offset);
        }
    }
}
