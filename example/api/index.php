<?php

require_once dirname(__FILE__).'/../configure.php';
require_once dirname(__FILE__).'/MyAuthClass.php';
require_once dirname(__FILE__).'/MyAPIFunction.php';

header('Access-Control-Allow-Origin: *');
if (isset($_GET['callback'])) {
    echo $_GET['callback'].'(';
}

if (isset($_POST['data']) && !is_array($_POST['data']) && !empty($_POST['data'])) {
    $stdInput = $_POST['data'];
} else {
    $stdInput = file_get_contents('php://input');
}

\TAS\API\APIRequest::$AuthenticationCallBack = array("\MyProject\MyAuthClass", 'ValidateAPIAccess');
\TAS\API\APIRequest::$FunctionList = \MyProject\MyAPIFunction::FunctionList();

$errorMessageString = '';
$parseDone = \TAS\API\APIRequest::ParseJson($stdInput, $errorMessageString);
if (is_bool($parseDone) && $parseDone == false) {
    if ($errorMessage != '') {
        echo json_encode(\TAS\API\APIResponse::CreateErrorResponse(array('message' => $errorMessageString)));
    } else {
        echo json_encode(\TAS\API\APIResponse::CreateErrorResponse('Incorrect input provided. Please check your input.'.print_r($_POST, true)));
    }
} else {
    echo \TAS\API\APIRequestHandler::Handle($parseDone);
}

if (isset($_GET['callback'])) {
    echo   ')';
}
