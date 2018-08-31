<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConfirmEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $hash)
    {
        if ($hash) {
            $customer = Customer::whereHash($hash)->first();

            if ($customer && $customer->confirmed == false) {
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

                    $response = $admd->put('users/' . $customer->swifty_id, [
                        'json' => [
                            'enabled' => true
                        ],
                        'headers' => [
                            'X-Auth-Token' => $token
                        ]
                    ]);

                    if ($response->getStatusCode() == 200) {
                        $customer->confirmed = true;
                        $customer->hash = null;
                        $customer->save();

                        Mail::to($customer->email)->send(new \App\Mail\Welcome($customer));
                        return redirect('/signin/?confirmed=1');
                    } else {
                        return 'Something wrong with account';
                    }
                } else {
                    return 'Something wrong with API';
                }
            } else {
                return 'User not found or already confirmed';
            }
        }
    }
}