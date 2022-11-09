<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;

class CustomHelper
{
    /**
     * @param $collections
     */
    public function sendMessage($collections)
    {
        if ($collections->count()) {
            foreach ($collections as $collect) {
                $url = env('SMS_URL');
                $url = str_replace("{{number}}", $collect->phone, $url);
                $url = str_replace("{{message}}", $collect->message, $url);
                Http::get($url);
            }
        }
    }
}
