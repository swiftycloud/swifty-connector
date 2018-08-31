<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Mail\PasswordResetEmail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{
    
    public function link(Request $request)
    {
        $customer = Customer::whereEmail($request->email)->first();

        if ($customer) {
            $customer->hash = md5($customer->email . time());
            $customer->save();
            Mail::to($customer->email)->send(new PasswordResetEmail($customer));
        }

        return ['status' => 'ok'];
    }

    public function reset(Request $request)
    {
        if ($request->has('hash') && $request->has('password')) {
            $customer = Customer::whereHash($request->hash)->first();

            if ($customer) {
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

                    $response = $admd->put('users/' . $customer->swifty_id . '/pass', [
                        'json' => [
                            'username' => $customer->swifty_id,
                            'password' => $request->password
                        ],
                        'headers' => [
                            'X-Auth-Token' => $token
                        ]
                    ]);

                    if ($response->getStatusCode() == 201) {
                        $customer->hash = null;
                        $customer->password = Hash::make($request->password);

                        if ($customer->save()) {
                            return ['status' => 'ok'];
                        } else {
                            return ['status' => 'error', 'message' => 'Customer info not saved'];
                        }
                    } else {
                        return ['status' => 'error', 'message' => 'Something wrong'];
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
