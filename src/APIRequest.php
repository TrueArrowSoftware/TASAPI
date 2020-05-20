<?php

namespace TAS\API;

/**
 * Class to parse and handle API request.
 */
class APIRequest
{
    /**
     * Request Type/function name to call.
     *
     * @var string
     */
    public $Type;

    /**
     * Request parameter specific to each function.
     *
     * @var array
     */
    public $Parameters;

    /**
     * Parameter for authnetication of each API Call.
     *
     * @var array
     */
    public $Authentication;

    /**
     * User information.
     *
     * @var array
     */
    public static $UserInfo;

    /**
     * Request options.
     *
     * @var array
     */
    public $Options;

    /**
     * FunctionList.
     * array.
     *
     * @var array
     */
    public static $FunctionList;

    /**
     * Callback function information for authentication. Callback function should return bool output. Also this function will set UserInfo variable as required by project.
     *
     * @var array|string
     */
    public static $AuthenticationCallBack;

    /**
     * Basic constructor.
     */
    public function __construct()
    {
        $this->_init();
    }

    /**
     * Basic initialization of all variables.
     */
    private function _init()
    {
        $this->Type = 'info';
        $this->Authentication = array(
            'username' => '',
            'authkey' => '',
        );
        $this->Parameters = array();
        $this->Options = array(
            'limit' => 100,
            'orderby' => 'id',
            'order' => 'asc',
            'page' => 1,
        );
    }

    /**
     * Validate the request for data, if valid it make some adjustment to request data.
     *
     * @todo : Parameter specific error generation, Rewrite to use $data to validate and not object.
     *
     * @param array $data
     *                    Exact Incoming request ex. $_POST after in PHP Array format
     */
    public static function Validate($data, APIRequest $apirequest, $parametermode = array())
    {
        if (!is_array($data)) {
            return 1;
        }
        if (!in_array($data['type'], array_keys(APIRequest::getFunctionList()))) {
            return 2;
        }
        if (isset($data['options']['limit']) && !is_numeric($data['options']['limit'])) {
            return 3;
        }
        if (isset($data['options']['page']) && !is_numeric($data['options']['page'])) {
            return 4;
        }
        if (isset($data['options']['order']) && !in_array(strtolower($data['options']['order']), array(
            'asc',
            'desc',
        ))) {
            return 5;
        }

        if (!isset($data['authentication']) || !isset($data['authentication']['username']) || !isset($data['authentication']['authkey'])) {
            return 6;
        }

        $authresult = \call_user_func(\TAS\API\APIRequest::$AuthenticationCallBack, $data['authentication']);
        if (!$authresult) {
            return 7;
        }

        // @todo: log all incoming calls for data analysis
        if (isset($data['options']['order'])) {
            $data['options']['order'] = strtolower($data['options']['order']);
        }

        return true;
    }

    /**
     * Parse JSON Data as API Request and APIRequest Object.
     *
     * @param string $jsonData
     * @param mixed  $errorMessageString
     */
    public static function ParseJson($jsonData, &$errorMessageString)
    {
        try {
            $data = json_decode($jsonData, true);
            $apiRequest = new \TAS\API\APIRequest();
            $validation = \TAS\API\APIRequest::Validate($data, $apiRequest);

            $errorMessageString = '';
            if (is_numeric($validation) && $validation >= 1 && $validation <= 7) {
                switch ($validation) {
                    case 7:
                        $errorMessageString = 'access denied';
                        break;
                    case 6:
                        $errorMessageString = 'no authorization information is provided or invalid parameter';
                        break;
                }
            }
            if (is_bool($validation)) {
                $apiRequest->Type = $data['type'];
                $apiRequest->Parameters = $data['parameters'];
                $apiRequest->Options = (isset($data['options']) ? $data['options'] : array());

                return $apiRequest;
            } else {
                return false;
            }
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Return a list of all API Function and their parameter information.
     */
    public static function getFunctionList()
    {
        if (!is_array(APIRequest::$FunctionList)) {
            return array(
                'info' => array(
                    'parameter' => array(),
                    'callback' => 'getInfo',
                ),
            );
        } else {
            return APIRequest::$FunctionList;
        }
    }
}
