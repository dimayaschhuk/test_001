<?php
if (!function_exists('getFlow')) {
    function getFlow()
    {
        return [
            'testFlow' => [
                'welcome',
                'testMethod',
                'testMetho',
                'testMeth',
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

        return getFlow()[$data['flow']][$key+1];
    }
}