<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Artisan;

class CustomHelper
{
    /**
     * @param array $data
     */
    public static function changeEnv($data = [])
    {
        if (count($data) > 0) {
            $path = app()->environmentFilePath();
            $env = file_get_contents($path);
            $env = preg_split('/(\r\n|\r|\n)/', $env);

            foreach ((array) $data as $key => $value) {
                if (preg_match('/\s/', $value) || preg_match('/(#)/', $value)) {
                    $value = '"' . $value . '"';
                }
                foreach ($env as $env_key => $env_value) {
                    $entry = explode("=", $env_value, 2);
                    if ($entry[0] == $key) {
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        $env[$env_key] = $env_value;
                    }
                }
            }

            $env = implode("\n", $env);
            file_put_contents($path, $env);

            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }
    }

    /**
     * @return mixed
     */
    public static function getCountryList()
    {
        $countries = json_decode(file_get_contents(storage_path('json/country.json')), true);
        $lists = [];
        foreach ($countries as $country) {
            $lists[] = [
                'id'   => $country['name'],
                'text' => $country['name'],
            ];
        }
        return $lists;
    }

}
