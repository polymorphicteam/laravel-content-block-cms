<?php

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource(config('cms.prefix'), '\Cswiley\Cms\CmsController');
});

Route::resource(cms_api_path(), '\Cswiley\Cms\API\CmsController');


