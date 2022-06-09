<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $all_helper_files = glob(app_path('Helpers') . '/*.php');
        foreach($all_helper_files as $key => $helper_file)
        {
            require_once $helper_file;
        }
    }
}
