<?php
if (!function_exists('getFlow')) {
    function getFlow()
    {
        return [
            'testFlow' => [
                'welcome',
                'sendTextEnterNameCulture',
                'searchCulture',
                'chooseGroup',
                'testMet',
                'testMe',
                'testM',
                'test',
            ],
        ];

    }
}

if (!function_exists('next_method')) {
    function next_method($data)
    {
        $key = array_search($data['method'], getFlow()[$data['flow']]);

        return getFlow()[$data['flow']][$key + 1];
    }
}

if (!function_exists('get_keyboard')) {
    function get_keyboard($keyboard)
    {
        $countButtons = count($keyboard);
        if ($countButtons > 3) {
            $keyboard = array_chunk($keyboard, 3);
        }else{
            return [$keyboard];
        }

        return $keyboard;
    }
}