<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//JUST ADD '->defaults("group", "Settings")' IF YOU WANT TO GROUP A NAV IN A DROPDOWN

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function(){
   return redirect()->route('login');
});

// API
Route::group([
        'prefix' => "api/"
    ], function (){
        Route::get('get/users', 'ApiController@users');
        Route::get('get/routes', 'ApiController@routes');
        Route::get('get/devices', 'ApiController@devices');
        Route::get('get/vehicles', 'ApiController@vehicles');
        Route::get('get/stations', 'ApiController@stations');
        Route::get('get/sales', 'ApiController@sales');
        Route::put('create/sale', 'ApiController@createSale');
        Route::patch('update/sale', 'ApiController@updateSale');
    }
);

Route::group([
        'middleware' => 'auth',
    ], function() {
        Route::get('/', "DashboardController@index")->name('dashboard');


        Route::get('/', 'DashboardController@index')
            ->defaults('sidebar', 1)
            ->defaults('icon', 'fas fa-list')
            ->defaults('name', 'Dashboard')
            ->defaults('roles', array('Admin'))
            ->name('dashboard')
            ->defaults('href', '/');

        // USER ROUTES
        $cname = "user";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-users")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("restore/", ucfirst($cname) . "Controller@restore")->name('restore');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
                Route::post("updatePassword/", ucfirst($cname) . "Controller@updatePassword")->name('updatePassword');
            }
        );

        // ROUTE ROUTES
        $cname = "route";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fad fa-route")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // STATION ROUTES
        $cname = "station";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // DEVICE ROUTES
        $cname = "device";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-mobile")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // VEHICLE ROUTES
        $cname = "vehicle";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-truck")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // SALE ROUTES
        $cname = "sale";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-solid fa-dollar-sign")
                    ->defaults("name", ucfirst($cname) . "s")
                    ->defaults("roles", array("Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("/manifest", ucfirst($cname) . "Controller@manifest")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-folder-open")
                    ->defaults("name", "Manifest")
                    ->defaults("roles", array("Admin", "Coast Guard"))
                    // ->defaults("group", "Settings")
                    ->name("manifest")
                    ->defaults("href", "sale/manifest");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
            }
        );

        // AUDIT TRAIL ROUTES
        $cname = "audit_trail";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("/", "AuditTrailController@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-shoe-prints")
                    ->defaults("name", "Audit Trail")
                    ->defaults("roles", array("Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // THEME ROUTES
        $cname = "theme";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // EXPORT ROUTES
        $cname = "export";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("sales/", ucfirst($cname) . "Controller@sales")->name('sales');
                Route::get("manifest/", ucfirst($cname) . "Controller@manifest")->name('manifest');
            }
        );

        // DATATABLES
        $cname = "datatable";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("user", ucfirst($cname) . "Controller@user")->name('user');
                Route::get("route", ucfirst($cname) . "Controller@route")->name('route');
                Route::get("station", ucfirst($cname) . "Controller@station")->name('station');
                Route::get("device", ucfirst($cname) . "Controller@device")->name('device');
                Route::get("vehicle", ucfirst($cname) . "Controller@vehicle")->name('vehicle');
                Route::get("sale", ucfirst($cname) . "Controller@sale")->name('sale');
                
                Route::get("audit_trails", ucfirst($cname) . "Controller@audit_trails")->name('audit_trails');
            }
        );
    }
);

require __DIR__.'/auth.php';