<?php

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }

    /**
     * Helper to grab the application url.
     *
     * @return mixed
     */
    function app_url()
    {
        return config('app.url');
    }
}