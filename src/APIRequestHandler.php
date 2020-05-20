<?php

namespace TAS\API;

/**
 * Handle the request and process them to generate output.
 */
class APIRequestHandler
{
    /**
     * Validate a function and call for request type, else return error.
     *
     * @param APIRequest $request
     */
    public static function Handle(APIRequest $request)
    {
        $ret = '';
        $functions = APIRequest::getFunctionList();
        if (isset($functions[$request->Type])) {
            $ret = call_user_func($functions[$request->Type]['callback'], $request);
        } else {
            $ret = APIResponse::CreateErrorResponse('Invalid call to function method, please contact support');
        }

        return $ret->ToJson();
    }

    /**
     * Return the Basic static message for verification.
     *
     * @param APIRequest $request
     */
    public static function getInfo(APIRequest $request)
    {
        return APIResponse::CreateSuccessResponse('This is test function call to check if API is working fine, and it is.');
    }
}
