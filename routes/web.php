<?php

use Illuminate\Support\Facades\Auth;
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

// LibreHealth Pages/Manual
Route::get('/manual/installation-guide', 'PagesController@getInstallationManual')
    ->name('manual.installation');

        /* =================================
            Application Routes
       ================================= */

Auth::routes();

Route::get('/', 'PagesController@index')->name('index');
Route::get('/about', 'PagesController@about')->name('about');
Route::get('/contact', 'PagesController@contact')->name('contact');
Route::get('/version', 'PagesController@showVersion')->name('ehr.version');
Route::get('/acknowledge-license-cert', 'PagesController@acknowledgeLicenseCert')
    ->name('acknowledge_license_cert');


        /*=================================
         Multi Language Routes
        =================================*/
Route::get('/lang/{lang}', 'LocaleController@setLocale')->name('locale.set');


        /* =================================
            LH EHR PORTAL Routes
        ================================= */

Route::group(
    [
        'prefix' => 'console/dashboard',
        'middleware' => ['role:super_admin|admin|user']
    ],
    function () {
        Route::get('', 'Admin\DashboardController@index')
            ->name('dashboard');

        // User Profiles/Settings and globals
        Route::get('/settings', 'Admin\DashboardController@settings')
            ->name('dashboard.settings');

        Route::get('/profile', 'Admin\DashboardController@profile')
            ->name('dashboard.profile');

        // calendar routes
        Route::get('/calendar', 'Admin\CalendarController@index')
            ->name('dashboard.calendar');

        // ======== Flow Board routes ========

        Route::get('/flow-board', 'Admin\FlowBoardController@index')
            ->name('dashboard.flow_board');

        // ======== Patient related routes ========
        Route::resource('/patients', 'Admin\Patient\PatientController');
        Route::group(
            [
                'prefix' => 'patients',
            //                'middleware' => 'select.patient',  TODO (add a middleware for selected patients)
                'namespace' => 'Admin\Patient'
            ],
            function () {
                Route::get('/select/{id}', 'PatientController@selectPatient')
                    ->name('patients.select');
                Route::get('/clear/{id}', 'PatientController@clearPatient')
                    ->name('patients.clear');

                // Patient History
                Route::get('/{id}/history', 'PatientHistoryController@index')
                    ->name('patients.history');
                Route::get('/{id}/history/edit', 'PatientHistoryController@edit')
                    ->name('patients.history.edit');

                Route::get('/{id}/documents', 'PatientController@patientDocuments')
                    ->name('patients.documents');
                Route::get('/{id}/reports', 'PatientController@patientReports')
                    ->name('patients.reports');
                Route::get('/{id}/appointments', 'PatientAppointmentController@index')
                    ->name('patients.appointments');
                Route::get('/{id}/transactions', 'PatientController@patientTransactions')
                    ->name('patients.transactions');
                Route::get('/{id}/ledger', 'PatientController@patientLedger')
                    ->name('patients.ledger');
            }
        );

        // ======== Users related routes ========
        Route::resource('/users', 'Admin\Patient\PatientController');


        // ======== Facility related routes ========
        Route::resource('/facilities', 'Admin\Facility\FacilityController');
    }
);


        /* =================================
             Installer Routes
       ================================= */
Route::group(
    [
        'prefix' => 'install'],
    function () {
        Route::get('', 'Installer\InstallerController@index')
            ->name('ehr_installer.index');
        Route::get('/requirements', 'Installer\RequirementsController@index')
            ->name('ehr_installer.requirements');
        Route::get('/file-permissions', 'Installer\FilePermissionController@index')
            ->name('ehr_installer.file_permissions');
        Route::get('/database', 'Installer\DatabaseController@index')
            ->name('ehr_installer.database');
    }
);
