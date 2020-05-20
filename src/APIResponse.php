<?php

namespace TAS\API;

/**
 * Generic Response Interface.
 */
class APIResponse
{
    /**
     * Can be Error or Success,.
     *
     * @var string
     */
    public $Type;

    /**
     * Response Output based on RequestType,.
     *
     * @var array
     */
    public $Output;

    public function ToJson()
    {
        return json_encode(array(
            'type' => $this->Type,
            'output' => $this->Output,
        ));
    }

    /**
     * Create Error message response.
     *
     * @param array $errorMessage
     */
    public static function CreateErrorResponse(array $errorMessage)
    {
        $response = new APIResponse();
        $response->Type = 'error';
        $response->Output = array(
            'error' => true,
            'message' => $errorMessage['message'],
        );
        
        foreach ($errorMessage as $i => $v) {
            if (strtolower($i) != 'message' && strtolower($i) != 'error' && strtolower($i) != 'success') {
                $response->Output[$i] = $v;
            }
        }

        return $response;
    }

    /**
     * Return the Object as output.
     *
     * @param array $obj
     */
    public static function CreateSuccessResponse(array $obj)
    {
        $response = new APIResponse();
        $response->Type = 'success';
        $response->Output = array(
            'message' => $obj['message'],
        );
        foreach ($obj as $i => $v) {
            if (strtolower($i) != 'message' && strtolower($i) != 'error' && strtolower($i) != 'success') {
                $response->Output[$i] = $v;
            }
        }

        return $response;
    }
}
