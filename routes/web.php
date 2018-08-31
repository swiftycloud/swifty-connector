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

Route::get('/customers/confirm/{hash}', 'ConfirmEmailController');

Route::get('/test', function () {
  $admd = new \GuzzleHttp\Client([
      'base_uri' => env('API_ADMD_ENDPOINT')
  ]);

  $response = $admd->post('login', [
      'json' => [
          'username' => env('API_USERNAME'),
          'password' => env('API_PASSWORD')
      ]
  ]);

  if ($response->hasHeader('X-Subject-Token')) {
      $token = $response->getHeader('X-Subject-Token')[0];

      $response = $admd->put('users/090d8a9aa728497cb0bc6054872fe663', [
          'json' => [
              'enabled' => true
          ],
          'headers' => [
              'X-Auth-Token' => $token
          ]
      ]);

      if ($response->getStatusCode() == 200) {
          return 'It is OK';
      } else {
          return 'Something wrong with account';
      }
  } else {
      return 'Something wrong with API';
  }
});

Route::get('/{vue_capture?}', function () {
  return view('vue');
})->where('vue_capture', '[\/\w\.-]*');
