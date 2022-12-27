<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Http\Request $request)
    {
        if(!empty(env('NGROK_URL')) && $request->server->has('HTTP_X_ORIGINAL_HOST')){
            $this->app['url']->forceRootUrl(env('NGROK_URL'));
        }

        view()->composer('*',function($view) {
            $theme = DB::table('themes');

            if(isset(auth()->user()->role) && auth()->user()->role == "Company"){
                $theme = $theme->where('company_id', auth()->user()->id);
                $view->with('theme', $theme->pluck('value', 'name'));
            }
            elseif(isset($_GET['u'])){
                $theme = $theme->where('company_id', $_GET['u']);
                $view->with('theme', $theme->pluck('value', 'name'));
            }
        });
    }
}
