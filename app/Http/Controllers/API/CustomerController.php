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
        $admd = new \GuzzleHttp\Client([
            'base_uri' => env('API_ADMD_ENDPOINT')
        ]);

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomer $request)
    {
        $customer = new Customer();
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->name = $request->name;

        // TODO: make hash temporary
        $customer->hash = md5($request->email . time());

        if ($customer->save()) {
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

                $response = $admd->post('users', [
                    'json' => [
                        'uid' => $request->email,
                        'password' => $request->password,
                        'name' => $request->name
                    ],
                    'headers' => [
                        'X-Auth-Token' => $token
                    ]
                ]);

                if ($response->getStatusCode() == 201) {
                    $data = json_decode($response->getBody()->getContents(), true);
                    $customer->swifty_id = $data['id'];
                    $customer->save();
                }
            }

            Mail::to($request->email)->send(new ConfirmEmail($customer));
        }

        return $customer;
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
