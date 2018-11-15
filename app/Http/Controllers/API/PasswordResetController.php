<?php

namespace App\Http\Controllers\API;

use App\CustomerPasswordReset;
use App\Mail\PasswordResetEmail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{
    
    public function link(Request $request)
    {
        $params = [
            'base_uri' => env('API_ADMD_ENDPOINT')
        ];

        if (env('VERIFY_SSL') == false) {
            $params['curl'] = array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false );
            $params['verify'] = false;
        }

        $admd = new \GuzzleHttp\Client($params);

        $response = $admd->post('login', [
            'json' => [
                'username' => env('API_USERNAME'),
                'password' => env('API_PASSWORD')
            ]
        ]);

        if ($response->hasHeader('X-Subject-Token')) {
            $token = $response->getHeader('X-Subject-Token')[0];

            $response = $admd->get('users', [
                'headers' => [
                    'X-Auth-Token' => $token
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $users = json_decode($response->getBody()->getContents(), true);

                foreach ($users as $user) {
                    if ($user['uid'] == $request->email) {
                        $password_reset = new CustomerPasswordReset;
                        $password_reset->email = $request->email;
                        $password_reset->token = md5($request->email . time());
                        $password_reset->save();

                        Mail::to($password_reset->email)->send(new PasswordResetEmail($password_reset));
                        return ['status' => 'ok'];
                    }
                }
            }
        }

        return ['status' => 'failed'];        
    }

    public function reset(Request $request)
    {
        if ($request->has('hash') && $request->has('password')) {
            $password_reset = CustomerPasswordReset::whereToken($request->hash)->first();

            if ($password_reset) {
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

                    $response = $admd->get('users', [
                        'headers' => [
                            'X-Auth-Token' => $token
                        ]
                    ]);

                    if ($response->getStatusCode() == 200) {
                        $users = json_decode($response->getBody()->getContents(), true);

                        foreach ($users as $user) {
                            if ($user['uid'] == $password_reset->email) {
                                $response = $admd->put('users/' . $user['id'] . '/pass', [
                                    'json' => [
                                        'username' => $user['id'],
                                        'password' => $request->password
                                    ],
                                    'headers' => [
                                        'X-Auth-Token' => $token
                                    ]
                                ]);

                                if ($response->getStatusCode() == 201) {
                                    CustomerPasswordReset::whereEmail($password_reset->email)->delete();
                                    return ['status' => 'ok'];
                                } else {
                                    return ['status' => 'error', 'message' => 'Something wrong'];
                                }
                            }
                        }
                    }                    
                }
            } else {
                return ['status' => 'error', 'message' => 'Link is broken'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Something wrong'];
        }
    }

}
