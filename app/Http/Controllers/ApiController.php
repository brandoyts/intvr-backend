<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function queryWithInverted(Request $request)
    {

        $url = $request->url;

        // check if url is valid
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json([
                'error' => true,
                'data' => null
            ], 400);
        }

        $queryResponse = Http::get($url);

        $originalResponse = json_decode($queryResponse->body(), true);



        $invertedObj = $this->invertKeysAndValues($originalResponse);
        $invertedJson = json_encode($invertedObj, JSON_UNESCAPED_UNICODE);

        $decodedInvertedJson = json_decode($invertedJson, true);



        return response()->json([
            'error' => false,
            'data' => [
                'original' => $originalResponse,
                'inverse' => json_decode($invertedJson, true),
            ]
        ], 200);
    }


    public function invertKeysAndValues($obj)
    {
        $invertedObj = array();
        foreach ($obj as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = $this->invertKeysAndValues($value);
            } else {
                $value = strrev($value);
            }
            $invertedObj[strrev($key)] = $value;
        }
        return $invertedObj;
    }
}
