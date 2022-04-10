<?php

if (! function_exists('notifer_config')) {
    /**
     * @param null|string $key
     * @param null|mixed $default
     * @return mixed|\Itoufo\Notifer\Contracts\ConfigContract
     */
    function notifer_config($key = null, $default = null)
    {
        $config = app('notifer.config');
        if (is_null($key)) {
            return $config;
        }

        return $config->get($key, $default);
    }
}
