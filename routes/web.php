<?php

Auth::routes(['register' => false,'reset' => false]);

Route::get('/', 'HomeController@index')->name('home');


$app_path = 'App';

##mail
Route::resource('/mail_template',$app_path.'\MailTemplateController');
Route::resource('/mail',$app_path.'\MailController');

//download
Route::get('/download/mail_template_attachment',$app_path.'\DownloadController@download_mail_template_attachment');
Route::get('/download/customer_import_excel_layout',$app_path.'\DownloadController@download_customer_import_excel_layout');

##customer
Route::post('/customer/import_file',$app_path.'\CustomerController@customer_import_file');
Route::post('/customer/import_data',$app_path.'\CustomerController@customer_import_data');
Route::get('/customer/import',$app_path.'\CustomerController@customer_import');
Route::resource('/customer',$app_path.'\CustomerController');


##admin
Route::resource('/admin/account',$app_path.'\AdminController');
Route::get('/admin/action_log',$app_path.'\AdminController@action_log');
Route::get('/admin/mail_log',$app_path.'\AdminController@mail_log');
Route::get('/admin/mail_log/{id}/view_body_html',$app_path.'\AdminController@mail_log_body_html');

###AJAX
Route::get('ajax/get_customer_info',$app_path.'\AjaxController@ajax_get_customer_info');
Route::post('ajax/test_mail',$app_path.'\AjaxController@ajax_test_mail');


