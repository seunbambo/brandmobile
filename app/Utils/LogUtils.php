<?php

namespace App\Utils;

use Illuminate\Http\Request;

final class LogUtils
{

    public static function getLogData(Request $request, $response, $serviceName, $errorMessage = null): array
    {

        $logData['method'] = $request->method();
        $logData['url'] = $request->fullUrl();
        $logData['header'] = $request->header();
        $logData['_Service'] = $serviceName;
        $logData['Request'] = array('time' => date('Y-m-d H:i:s'), 'data' => $request->all());
        $logData['Response'] = array('time' => date('Y-m-d H:i:s'), 'data' => json_encode($response));

        return $logData;
    }
}
