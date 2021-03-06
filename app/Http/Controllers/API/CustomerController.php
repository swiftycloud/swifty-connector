<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Requests\StoreCustomer;
use App\Mail\ConfirmEmail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{

    public function login(Request $request)
    {
        $params = [
            'base_uri' => env('API_ADMD_ENDPOINT')
        ];

        if (env('VERIFY_SSL') == false) {
            $params['curl'] = array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false );
            $params['verify'] = false;
        }

        $admd = new \GuzzleHttp\Client($params);

        try {
            $response = $admd->post('login', [
                'json' => [
                    'username' => $request->email,
                    'password' => $request->password
                ]
            ]);

            if ($response->getStatusCode() == 200 && $response->hasHeader('X-Subject-Token')) {
                $data = json_decode($response->getBody()->getContents(), true);

                return [
                    'status' => 'ok',
                    'token' => $response->getHeader('X-Subject-Token')[0],
                    'expires' => $data['expires'],
                    'redirect_to' => env('DASHBOARD_URL'),
                    'domain' => env('MAIN_DOMAIN')
                ];
            } else {
                return ['status' => 'error', 'message' => 'Can not log in'];
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                return ['status' => 'error', 'message' => 'Incorrect email or password'];
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something was wrong, try again'], 418);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ..
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomer $request)
    {
        $params = [
            'base_uri' => env('API_ADMD_ENDPOINT')
        ];

        if (env('VERIFY_SSL') == false) {
            $params['curl'] = array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false );
            $params['verify'] = false;
        }

        $admd = new \GuzzleHttp\Client($params);

        try {
            $response = $admd->post('login', [
                'json' => [
                    'username' => env('API_USERNAME'),
                    'password' => env('API_PASSWORD')
                ]
            ]);

            if ($response->hasHeader('X-Subject-Token')) {
                $token = $response->getHeader('X-Subject-Token')[0];

                $response = $admd->post('users', [
                    'json' => [
                        'uid' => $request->email,
                        'pass' => $request->password,
                        'name' => $request->name
                    ],
                    'headers' => [
                        'X-Auth-Token' => $token
                    ]
                ]);

                if ($response->getStatusCode() == 201) {
                    $data = json_decode($response->getBody()->getContents(), true);

                    $customer = new Customer();
                    $customer->email = $request->email;
                    $customer->name = $request->name;
                    $customer->subscribed = $request->subscribed;

                    // TODO: make hash temporary
                    $customer->hash = md5($request->email . time());
                    $customer->swifty_id = $data['id'];
                    $customer->save();

                    if ($customer->save()) {
                        Mail::to($request->email)->send(new ConfirmEmail($customer));
                        return $customer;
                    } else {
                        return false;
                    }
                }
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() == 409) {
                return response()->json(['errors' => ['email' => ['User already exists']]], 409);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something was wrong, try again', 'code' => $e->getResponse()->getStatusCode()], 418);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
