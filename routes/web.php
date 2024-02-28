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
        // Route::get('sendVerification', 'ApiController@sendVerification')->name('sendVerification');
        // Route::get('verify', 'ApiController@verify')->name('verify');

        // Route::post('get/users', 'ApiController@users');
        // Route::post('get/routes', 'ApiController@routes');
        // Route::post('get/devices', 'ApiController@devices');
        // Route::post('get/vehicles', 'ApiController@vehicles');
        // Route::post('get/categories', 'ApiController@categories');
        // Route::post('get/stations', 'ApiController@stations');
        // Route::post('get/sales', 'ApiController@sales');

        Route::put('create/transaction', 'ApiController@createTransaction');
        Route::get('test', 'ApiController@test');
        // Route::put('create/vehicle', 'ApiController@createVehicle');
        // Route::put('create/ledger-entry', 'ApiController@createLedgerEntry');

        // Route::post('update/sale', 'ApiController@updateSale');
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
            ->defaults('roles', array('Super Admin', 'Admin', 'Branch'))
            ->name('dashboard')
            ->defaults('href', '/');

        // USER 2 FOR STAFFS ROUTES
        $cname = "user";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/staffs", ucfirst($cname) . "Controller@index2")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-users")
                    ->defaults("name", "Staff")
                    ->defaults("roles", array("Super Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname/staffs");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store2/", ucfirst($cname) . "Controller@store2")->name('store2');
                Route::post("restore/", ucfirst($cname) . "Controller@restore")->name('restore');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
                Route::post("updatePassword/", ucfirst($cname) . "Controller@updatePassword")->name('updatePassword');
            }
        );

        // USER ROUTES
        $cname = "user";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/clients", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-users")
                    ->defaults("name", "Client")
                    ->defaults("roles", array("Super Admin", "Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname/clients");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // LOAN ROUTES
        $cname = "loan";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fa-light fa-money-bill")
                    ->defaults("name", "Loan")
                    ->defaults("roles", array("Super Admin", "Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
                Route::post("update2/", ucfirst($cname) . "Controller@update2")->name('update2');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
            }
        );

        // TRANSACTIONN ROUTES
        $cname = "transaction";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){

                Route::get("/", ucfirst($cname) . "Controller@index")
                    ->defaults("sidebar", 1)
                    ->defaults("icon", "fas fa-hand-holding-circle-dollar")
                    ->defaults("name", "Transaction")
                    ->defaults("roles", array("Super Admin", "Admin"))
                    // ->defaults("group", "Settings")
                    ->name($cname)
                    ->defaults("href", "/$cname");

                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // THEME ROUTES
        $cname = "branch";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("store/", ucfirst($cname) . "Controller@store")->name('store');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
                Route::post("delete/", ucfirst($cname) . "Controller@delete")->name('delete');
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
                    ->defaults("roles", array("Super Admin"))
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

        // TRANSACTION ROUTES
        $cname = "transaction";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("get/", ucfirst($cname) . "Controller@get")->name('get');
                Route::post("update/", ucfirst($cname) . "Controller@update")->name('update');
            }
        );

        // REPORT ROUTES
        $cname = "report";
        Route::group([
                'as' => "$cname.",
                'prefix' => "$cname/"
            ], function () use($cname){
                Route::get("payments/", ucfirst($cname) . "Controller@payments")->name('payments');
            }
        );

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
                Route::get("user2", ucfirst($cname) . "Controller@user2")->name('user2');
                Route::get("branch", ucfirst($cname) . "Controller@branch")->name('branch');
                Route::get("loans", ucfirst($cname) . "Controller@loans")->name('loans');
                Route::get("transactions", ucfirst($cname) . "Controller@transactions")->name('transactions');
                
                Route::get("audit_trails", ucfirst($cname) . "Controller@audit_trails")->name('audit_trails');
            }
        );
    }
);

require __DIR__.'/auth.php';