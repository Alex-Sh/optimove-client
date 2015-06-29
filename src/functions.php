<?php

if (!function_exists('isValidGuid')) {
    /**
    * Determine if supplied string is a valid GUID
    *
    * @param string $guid String to validate
    * @return boolean
    */
    function isValidGuid($guid, $caseSensitive = FALSE)
    {
        if (is_string($guid) && strlen($guid) === 36) {
            $checkPattern = '~^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$~';
            if ($caseSensitive === FALSE) {
                $checkPattern .= 'i';
                return (boolean) preg_match($checkPattern, $guid);
            }
        }

        return FALSE;
    }
}
