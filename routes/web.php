    <?php

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


Route::get('/login', function () {
    return view('welcome');
});

Auth::routes(
    [
        'reset' => false,
        'verify' => false,
        'register' => false,
    ]
);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/performance-management-dashboard', 'PerformanceManagementController@index')->name('performaceManagementDashboard');
Route::get('/trainning-management-dashboard', 'TrainningManagementController@index')->name('trainningManagementDashboard');
Route::get('/reset-all-status', 'ApprovalController@resetAllStatus')->name('resetAllStatus');




Route::group(['middleware' => ['role:employees|department_managers|director|super-admin|general_director']], function () {

	Route::group(['prefix'=>'performace-management'],function(){
		Route::get('/performance-management-report/{id}', 'HomeController@getPerformaceManagement')->name('performaceManagement');
        Route::get('/print-performance-report', 'PdfController@printPerformanceReport')->name('printPerformanceReport');

        Route::post('/search-performance-report/{id}', 'HomeController@getPerformaceManagement')->name('searchPerformanceReport');

	});
});

Route::group(['middleware' => ['role:department_managers|super-admin']], function () {
	Route::get('/department-annual-training-plan/{id}','HomeController@getDATP')->name('DATP');


	Route::match(['get', 'post'], '/approving-my-employees-msc-objectives/approving-my-employees-annual-msc-objectives/{id}','HomeController@getAMEAMO')->name('AMEAMO');
	Route::match(['get', 'post'], '/approving-my-employees-msc-objectives/approving-my-employees-monthly-msc-objectives/{id}','HomeController@getAMEMMO')->name('AMEMMO');
    Route::post('/approve-my-employees-annual-msc/{id}','ApprovalController@approveMyEmployeeMscAnnual')->name('approveMyEmployeeMscAnnual');
    Route::post('/approve-my-employees-monthly-msc/{id}','ApprovalController@approveMyEmployeeMscMonthly')->name('approveMyEmployeeMscMonthly');
    Route::post('/reject-my-employees-annual-msc/{id}','ApprovalController@rejectMyEmployeeMscAnnual')->name('rejectMyEmployeeMscAnnual');
    Route::post('/reject-my-employees-monthly-msc/{id}','ApprovalController@rejectMyEmployeeMscMonthly')->name('rejectMyEmployeeMscMonthly');

    Route::group(['prefix'=>'approving-my-employees-performance'], function() {
	    Route::get('approving-my-employees-annual-performance/{id}','HomeController@getAMEAP')->name('AMEAP');
		Route::get('approving-my-employees-monthly-performance/{id}','HomeController@getAMEMP')->name('AMEMP');
        Route::post('approving-my-employees-annual-rate/{id}','ApprovalController@approveMyEmployeeRateAnnual')->name('approveMyEmployeeRateAnnual');
        Route::post('approving-my-employees-monthly-rate/{id}','ApprovalController@approveMyEmployeeRateMonthly')->name('approveMyEmployeeRateMonthly');
        Route::post('reject-my-employees-annual-rate/{id}','ApprovalController@rejectMyEmployeeRateAnnual')->name('rejectMyEmployeeRateAnnual');
        Route::post('reject-my-employees-monthly-rate/{id}','ApprovalController@rejectMyEmployeeRateMonthly')->name('rejectMyEmployeeRateMonthly');
	});

	Route::post('search-approving-my-employees-monthly-performance/{id}','HomeController@searchAMEMP')->name('searchAMEMP');
	Route::post('search-approving-my-employees-annual-performance/{id}','HomeController@searchAMEAP')->name('searchAMEAP');

});

Route::group(['middleware' => ['role:department_managers|employees|general_director|super-admin']], function () {

    Route::get('/building-my-personal-development-plan/{id}', 'HomeController@getBMPDP')->name('BMPDP');
    Route::match(['get', 'post'], '/building-my-monthly-msc-objectives/{id}', 'HomeController@getBMMMO')->name('BMMMO');
    Route::match(['get', 'post'], '/building-my-annual-msc-objectives/{id}', 'HomeController@getBMAMO')->name('BMAMO');
    Route::match(['get', 'post'], '/unlock-my-annual-msc-objectives/{id}', 'HomeController@unlockBMAMO')->name('unlockBMAMO');
    Route::post('/submit-monthly-msc-objectives/{id}', 'ApprovalController@submitMscMothy')->name('submitMscMothy');
    Route::post('/submit-my-annual-msc-objectives/{id}', 'ApprovalController@submitMscAnnual')->name('submitMscAnnual');

    Route::post('/save-monthly-msc-objectives/{id}', 'MscController@saveMscMonthly')->name('saveMscMonthly');
    Route::post('/save-annual-msc-objectives/{id}', 'MscController@saveMscAnnual')->name('saveMscAnnual');

    Route::post('/search-search-msc-objectives/{id}', 'HomeController@searchMscMonthly')->name('searchMscMonthly');
     Route::post('/search-annual-msc-objectives/{id}', 'HomeController@searchMscAnnual')->name('searchMscAnnual');

    Route::group(['prefix' => 'rating-performance'], function () {
        Route::match(['get', 'post'], '/rating-my-annual-performance/{id}', 'HomeController@getRMAP')->name('RMAP');
        Route::match(['get', 'post'], '/rating-my-monthly-performance/{id}', 'HomeController@getRMMP')->name('RMMP');
        Route::post('/submit-rating-my-annual/{id}', 'ApprovalController@submitRateAnnual')->name('submitRateAnnual');
        Route::post('/submit-rating-my-monthly/{id}', 'ApprovalController@submitRateMonthy')->name('submitRateMonthy');
        Route::post('/submit-first-rating-my-monthly/{id}', 'ApprovalController@submitFirstRMMP')->name('submitFirstRMMP');
    });

    Route::post('save-rate-monthly-performance/{id}', 'SaveController@saveRMMP')->name('saveRMMP');
    Route::post('save-rate-annual-performance/{id}', 'SaveController@saveRMAP')->name('saveRMAP');
    Route::post('review-rate-annual-performance/{id}', 'ApprovalController@reviewRMAP')->name('reviewRMAP');

    Route::post('search-rate-monthly-performance/{id}', 'HomeController@searchRMMP')->name('searchRMMP');
    Route::post('create-new-report-rate-monthlr-performance/{id}','SaveController@createRMMP')->name('createRMMP');
    Route::post('search-rate-annual-performance/{id}', 'HomeController@searchRMAP')->name('searchRMAP');

    Route::group(['prefix'=>'export'], function (){

        Route::post('export', 'ExcelMscController@export')->name('export');


    });

});
