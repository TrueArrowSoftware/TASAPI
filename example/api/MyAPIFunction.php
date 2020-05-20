<?php

namespace MyProject;

class MyAPIFunction
{
    public static function FunctionList()
    {
        return array(
            'info' => array(
                'parameter' => array(),
                'callback' => array('\MyProject\MyAPIFunction', 'getInfo'),
            ),
            'SomeProcess' => array(
                'parameter' => array(),
                'callback' => array('\MyProject\MyAPIFunction', 'SomeProcess'),
            ),
        );
    }

    public static function getInfo()
    {
        return \TAS\API\APIResponse::CreateSuccessResponse(array('message' => 'My GetInfo'));
    }

    public static function SomeProcess()
    {
        return \TAS\API\APIResponse::CreateSuccessResponse(array('message' => '', 'data' => array('Some array output', 'With 2 elements')));
    }
}
