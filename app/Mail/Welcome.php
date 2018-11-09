<?php

namespace App\Mail;

use App\Customer;
use Sichikawa\LaravelSendgridDriver\SendGrid;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Welcome extends Mailable
{
    use Queueable, SerializesModels, SendGrid;

    public $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->view('emails.customers.welcome')
                        ->subject('Swifty: вы успешно зарегистрированы')
                        ->with([
                            'customer' => $this->customer->toArray(),
                            'button_url' => env('DASHBOARD_URL')
                        ]);

        if (env('MAIL_DRIVER') == 'sendgrid') {
            $message->sendgrid([
                'template_id' => env('SENDGRID_WELCOME_TPL'),
                'personalizations' => [
                    [
                        'dynamic_template_data' => [
                            'customer' => $this->customer->toArray(),
                            'button_url' => env('DASHBOARD_URL')
                        ]
                    ]
                ]
            ]);
        }

        return $message;
    }
}
