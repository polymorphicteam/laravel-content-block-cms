<?php

if (!function_exists('path_builder')) {
    function path_builder($path)
    {
        return preg_replace('/\/[\/]+/', '/', implode('/', $path));
    }

}

if (!function_exists('cms_assets')) {
    function cms_assets($path, $secure = null)
    {
        $base = config('cms.assets_path');
        return asset($base . '/' . $path, $secure);
    }
}

if (!function_exists('cms_path')) {
    function cms_path($path = '')
    {
        $base = ['/', config('cms.prefix')];
        if (! empty($path)) {
            $base[] = $path;
        }

        return path_builder($base);
    }
}

if (!function_exists('cms_api_path')) {
    function cms_api_path($path = '')
    {
        $base = [
            '/',
            config('cms.api')
        ];
        if (!empty($path)) {
            $base[] = $path;
        }

        return path_builder($base);
    }
}
