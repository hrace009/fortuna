<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

use Illuminate\Support\Facades\Route;

Route::resource('static_pages', 'Admin\Page\PageController');

Route::group(['middleware' => ['auth:api', 'throttle:50,1']], static function () {

    // File uploads...
    Route::post('media_uploads', 'FilePondUploadController@upload');
    Route::delete('media_uploads', 'FilePondUploadController@delete');

    // Linked Accounts
    Route::prefix('game_accounts')->group(static function () {
        Route::get('all/paginate', 'User\Game\AccountController@index');
        Route::get('all', 'User\Game\AccountController@index');
        Route::post('create_game_account', 'User\Game\RegisterController@store');

        Route::prefix('account')->group(static function () {
            Route::get('{id}', 'User\Game\AccountController@show');
            Route::patch('{id}', 'User\Game\PasswordController@update');
        });
    });

    // Donate
    Route::resource('donate', 'User\Donate\DonateController');
    Route::get('gold_packages', 'User\Donate\GoldPackageController');
    Route::get('cash_packages', 'User\Donate\DonateController@getCashPackages');
    // payments
    Route::patch('payments', 'User\Donate\PaymentsController@process');
    Route::get('payment_gateways', 'User\Donate\PaymentGatewaysController');
    Route::patch('payment/{donate}', 'User\Donate\PaymentsController@update');
    Route::post('payments/execute', 'User\Donate\PaymentsController@execute');
    Route::post('paypal/execute', 'User\Donate\PaymentsController@execute');

    // User settings
    Route::prefix('settings')->group(static function () {
        Route::post('profile/avatar', 'User\Profile\AvatarController@store');
        Route::patch('profile', 'User\Profile\ProfileController@update');
        Route::patch('password', 'User\Profile\PasswordController@update');
    });

    // Notifications
    Route::prefix('notifications')->group(static function () {
        Route::get('{offset}/{limit}', 'User\NotificationsController@notifications');
        Route::get('/', 'User\NotificationsController@unreadNotifications');
        Route::get('all', 'User\NotificationsController@allNotifications');
        Route::patch('{notification}', 'User\NotificationsController@markAsRead');
        Route::delete('mark_all_as_read', 'User\NotificationsController@markAllAsRead');
    });

    // Route for game
    Route::prefix('game')->group(static function () {
        Route::get('check_status', 'User\Game\RolesController@checkStatus');
        Route::post('characters', 'User\Game\RolesController@getRoles');
        Route::post('update_characters', 'User\Game\RolesController@updateCharacters');
        // Route for game logs
        Route::prefix('logs_service')->group(static function () {
            Route::get('game_trades/{roleid}', 'User\Game\LogsController@getTrades');
            Route::get('game_loots/{roleid}', 'User\Game\LogsController@getLoots');
        });

        // Route for game chracter services
        Route::prefix('game_service')->group(static function () {
            Route::get('available_services', 'User\Game\ServicesController@index');
            Route::post('execute_service', 'User\Game\ServicesController@execute');
            Route::post('process_service', 'User\Game\ServicesController@process');
        });

        Route::prefix('faction_icon')->group(static function () {
            Route::get('user_factions', 'User\Game\FactionIconController@index');
            Route::post('user_factions', 'User\Game\FactionIconController@upload');
        });
    });

    // Audit (activity)
    Route::get('audit_actions', 'User\AuditController@activity');

    // Logout
    Route::post('logout', 'Auth\LoginController@logout');

    // User
    Route::get('user', 'User\UserController@getUser');
    Route::get('user/latest_order', 'User\UserController@getLatestOrder');
    Route::post('user/update-server', 'User\UserController@updateServer');
    Route::patch('user/update_document', 'User\UserController@updateDocument');

    Route::get('/dummy', 'User\Ticket\TicketController@test');
    // Ticket
    Route::prefix('tickets')->group(static function () {
        Route::get('/', 'User\Ticket\TicketController@index');
        Route::post('/', 'User\Ticket\TicketController@store');
        Route::get('{ticket}', 'User\Ticket\TicketController@show');
        Route::delete('{ticket}', 'User\Ticket\TicketController@destroy');
        Route::resource('{ticket}/reply', 'User\Ticket\ReplyController')->only(['store']);
    });
    //Route::get('ticket/categories', 'User\Ticket\TicketController@categories');
    Route::get('ticket/categories', 'User\Ticket\TicketController@categories');

    // Two Factor
    /* Two Factor */
    Route::prefix('two_factor_auth')->group(static function () {
        Route::post('select_method', 'User\Profile\SecurityController@index');
        Route::patch('authenticator_verify', 'User\Profile\SecurityController@authenticator');
        Route::delete('/', 'User\Profile\SecurityController@disable');
        Route::get('backup_codes', 'User\Profile\SecurityController@getBackupCodes');
    });
});

Route::group(['middleware' => 'guest:api'], static function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('2fa', ['uses' => 'Auth\SecurityController@security']);
    Route::post('2fa/backup', ['uses' => 'Auth\SecurityController@backup']);
    Route::post('register', 'Auth\RegisterController@register');
    Route::patch('register/activate/{token}', 'Auth\ActivationController@confirmEmail');
    Route::post('auth/forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset/{token}', 'Auth\ResetPasswordController@reset');
});
