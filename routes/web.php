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
// Route::group([
//         'prefix' => "api/"
//     ], function (){
//         Route::get('sendVerification', 'ApiController@sendVerification')->name('sendVerification');
//         Route::get('verify', 'ApiController@verify')->name('verify');

//         Route::post('get/users', 'ApiController@users');
//         Route::post('get/routes', 'ApiController@routes');
//         Route::post('get/devices', 'ApiController@devices');
//         Route::post('get/vehicles', 'ApiController@vehicles');
//         Route::post('get/categories', 'ApiController@categories');
//         Route::post('get/stations', 'ApiController@stations');
//         Route::post('get/sales', 'ApiController@sales');

//         Route::put('create/sale', 'ApiController@createSale');
//         Route::put('create/vehicle', 'ApiController@createVehicle');
//         Route::put('create/ledger-entry', 'ApiController@createLedgerEntry');

//         Route::post('update/sale', 'ApiController@updateSale');
//     }
// );

Route::group([
        'middleware' => 'auth',
    ], function() {
        Route::get('/', "DashboardController@index")->name('dashboard');

        Route::get('/', 'DashboardController@index')
            ->defaults('sidebar', 1)
            ->defaults('icon', 'fas fa-list')
            ->defaults('name', 'Dashboard')
            ->defaults('roles', array('Admin', 'Branch'))
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

        // COMPANY ROUTES
        $cname = "company";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", "CompanyController@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-sharp fa-solid fa-buildings")
                    ->defaults("name", "Companies")
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

                Route::get("get/", "AuditTrailController@get")->name('get');
                Route::post("store/", "AuditTrailController@store")->name('store');
                Route::post("delete/", "AuditTrailController@delete")->name('delete');
                Route::post("update/", "AuditTrailController@update")->name('update');
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

        // REPORT ROUTES
        // $cname = "report";
        // Route::group([
        //         'as' => "$cname.",
        //         'prefix' => "$cname/"
        //     ], function () use($cname){
        //         Route::get("sales/", ucfirst($cname) . "Controller@sales")->name('sales');
        //     }
        // );

        // EXPORT ROUTES
        // $cname = "export";
        // Route::group([
        //         'as' => "$cname.",
        //         'prefix' => "$cname/"
        //     ], function () use($cname){
        //         Route::get("sales/", ucfirst($cname) . "Controller@sales")->name('sales');
        //         Route::get("manifest/", ucfirst($cname) . "Controller@manifest")->name('manifest');
        //     }
        // );

        // DATATABLES
        $cname = "datatable";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("user", ucfirst($cname) . "Controller@user")->name('user');
                Route::get("company", ucfirst($cname) . "Controller@company")->name('company');
                
                Route::get("audit_trails", ucfirst($cname) . "Controller@audit_trails")->name('audit_trails');
            }
        );
    }
);

require __DIR__.'/auth.php';