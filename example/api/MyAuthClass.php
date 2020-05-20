<?php

namespace MyProject;

class MyAuthClass
{
    public static function ValidateAPIAccess(array $authData)
    {
        if ($authData['username'] == 'demo' && $authData['authkey'] == 'demo') {
            return true;
        }

        return false;
    }
}
