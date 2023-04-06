<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */


    // $router->get('analytics', 'AuthService@analytics');

$router->get('/key', function () {
    return \Illuminate\Support\Str::random(32);
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/activated', function () use ($router) {
    return view('activated');
});


$router->get('v1/users/activate-accounts-x', 'AuthService@activateAccountsx');

$router->group(['prefix' => 'peripheral/', 'middleware' => 'peripheralAuth'], function ($router) {
    $router->post('{access_token}/deviceLogin', 'Hms6500@loginBackground');
    $router->post('{access_token}/basicInfo', 'Hms6500@basicInfo');
    $router->post('{access_token}/physicalReport', 'Hms6500@physicalReport');
    $router->post('{access_token}/controlFile', 'Hms6500@controlFile');
    $router->post('{access_token}/originalData', 'Hms6500@originalData');
    $router->post('{access_token}/trendData', 'Hms6500@trendData');
});

$router->group(['prefix' => 'v1/', 'middleware' => 'clientAuth'], function ($router) {

    $router->get('analytics', 'AuthService@analytics');
    $router->get('teleconsult/token-validate', 'AuthService@consultTokenValidate');
    $router->get('teleconsult/summary', 'AuthService@consultSummary');
    $router->get('teleconsult/summaryPdf', 'AuthService@consultSummaryPdf');
    $router->get('patient/summary', 'AuthService@patientSummary');
    $router->get('patient/summaryPdf', 'AuthService@patientSummaryPdf');
    $router->get('patient/downloadReport/{id}', 'AuthService@downloadReport');
    $router->post('patient/ConsultCache', 'AuthService@ConsultCache');

    $router->post('freeze-phr-emr', 'AuthService@freezePhrEmr');

    $router->group(['prefix' => 'peripheral-ev/'], function ($router) {
        $router->post('login', 'BluetoothPeripheralService@login');
        $router->post('capture', ['middleware' => 'userAuth', 'uses' => 'BluetoothPeripheralService@capture']);
    });

    $router->group([], function ($router) {
        $router->group(['prefix' => 'users'], function ($router) {
            $router->post('authenticate', 'AuthService@generalLogin');
            $router->post('forgot-password/email', 'AuthService@forgotPasswordEmail');
            $router->post('forgot-password/email-otp', 'AuthService@forgotPasswordEmailOtp');
            $router->post('verify-email', 'AuthService@verifyEmail');
            $router->post('verify-otp', 'AuthService@verifyOtp');
            $router->post('resend-otp', 'AuthService@resendOtp');
            $router->get('get-master-x', 'AuthService@getMasterx');
            $router->get('get-timezone-x', 'AuthService@getTimezonex');
            $router->post('save-patients-x', 'AuthService@savePatientsx');
            $router->post('save-providers-x', 'AuthService@saveProvidersx');
            $router->post('save-hospitals-x', 'AuthService@saveHospitalsx');
            $router->get('auth-guest-x', 'AuthService@authGuestx');
        });

        $router->get('resource/masters/list', 'MasterService@masterList');
        $router->get('resource/custom/masters/list', 'MasterService@customMasterList');

    });

    $router->group(['middleware' => 'ApiServiceAuth'], function ($router) {

        // AutoLogin User
        $router->post('auto-login', 'BluetoothPeripheralService@autoLogin');

        $router->group(['prefix' => 'xapi'], function ($router) {

            $router->group(['middleware' => ['resource']], function ($router) {
                $router->get('{resource}/List', ['uses' => 'ResourceService@list']);
                $router->get('{resource}/Fetch/{id:[0-9]+}', ['uses' => 'ResourceService@fetch']);
                $router->put('{resource}/Update/{id:[0-9]+}', ['uses' => 'ResourceService@update']);
                $router->patch('{resource}/PartialUpdate/{id:[0-9]+}', ['uses' => 'ResourceService@partialUpdate']);
                $router->post('{resource}/Create', ['uses' => 'ResourceService@create']);
                $router->get('{resource}/getAll', ['uses' => 'ResourceService@getAll']);
                $router->delete('{resource}/Delete/{id:[0-9]+}', ['uses' => 'ResourceService@delete']);

            });
            $router->get('vitalDashboards', 'AuthService@vitalDashboards');
            $router->get('get-document-url', 'UtilService@getSignedUrl');
            $router->post('uploadDocs', 'AuthService@uploadDocs');

        });

    });

    $router->group(['middleware' => 'userAuth'], function ($router) {

        $router->get('healthPDF', 'AuthService@healthPDFx');
        $router->get('vitalsPDF', 'AuthService@vitalsPDFx');
        $router->get('activityWellnessPDF', 'AuthService@activityWellnessPDFx');
        $router->get('docsPDF', 'AuthService@docsPDFx');
        $router->get('historyPDF', 'AuthService@historyPDFx');
        $router->get('masterPDFx', 'AuthService@masterPDFx');

        $router->get('vitalsPDF_globalx', 'AuthService@vitalsPDF_globalx');
        $router->get('healthPDF_globalx', 'AuthService@healthPDF_globalx');
        $router->get('docsPDF_globalx', 'AuthService@docsPDF_globalx');
        $router->get('activityWellnessPDF_globalx', 'AuthService@activityWellnessPDF_globalx');
        $router->get('immunisationPDF_globalx', 'AuthService@immunisationPDF_globalx');
        $router->get('historyPDF_globalx', 'AuthService@historyPDF_globalx');
        $router->get('familyHistoryPDF_globalx', 'AuthService@familyHistoryPDF_globalx');
        $router->get('ReviewOfSystem_globalx', 'AuthService@ReviewOfSystem_globalx');
        $router->get('physicalExamination_globalx', 'AuthService@physicalExamination_globalx');
        $router->get('assessmentPDF_globalx', 'AuthService@assessmentPDF_globalx');
            $router->get('vitalDashboards', 'AuthService@vitalDashboards');

        $router->group(['prefix' => 'users'], function ($router) {
            $router->patch('set-password', 'AuthService@setPassword');
            $router->patch('change-password', 'AuthService@changePassword');
            $router->patch('twofa', 'AuthService@twofa');
            $router->patch('communication', 'AuthService@communication');
            $router->get('info', 'AuthService@info');
            $router->get('providerinfo', 'AuthService@providerinfo');
            $router->get('patientinfo', 'AuthService@patientinfo');
            $router->get('userDetails', 'AuthService@userDetails');
            $router->post('uploadDocs', 'AuthService@uploadDocs');
            $router->post('uploadAvatar', 'AuthService@uploadAvatar');
            // $router->post('verify-otp', 'AuthService@verifyOtp');
            // $router->post('resend-otp', 'AuthService@resendOtp');
            $router->patch('{id:[0-9]+}/change-password', ['middleware' => 'acl:users,change-user-password', 'uses' => 'AuthService@changeUserPassword']);
        });

        // Patch Periperal OTP
        $router->patch('/getOtp/{id:[0-9]+}', 'AuthService@getPeriperalOtp');
        
        // Get Document File URL
        $router->get('/get-document-url', 'UtilService@getSignedUrl');

        // Consult provider list
        $router->get('resource/available-providers/list', 'ProviderService@list');

        // Teleconsult List
        $router->get('resource/consults', 'TeleConsultService@list');

        /*Resource Operations*/
        $router->group(['prefix' => '/resource', 'middleware' => ['resource']], function ($router) {
            $router->post('{resource}', ['middleware' => 'acl:resource,create', 'uses' => 'ResourceService@create']);
            $router->put('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,update', 'uses' => 'ResourceService@update']);
            $router->patch('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,update', 'uses' => 'ResourceService@partialUpdate']);
            $router->delete('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,delete', 'uses' => 'ResourceService@delete']);
            $router->get('{resource}', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@list']);
            $router->get('{resource}/all', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@getAll']);
            $router->get('{resource}/first', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@getFirst']);
            $router->get('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@fetch']);
            $router->get('{resource}/aggregate', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@aggregate']);
        });
    });
});
