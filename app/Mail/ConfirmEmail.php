<?php

namespace App\Mail;

use App\Customer;
use Sichikawa\LaravelSendgridDriver\SendGrid;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmEmail extends Mailable
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
        $message = $this->view('emails.customers.confirm')
                        ->with([
                            'customer' => $this->customer->toArray(),
                            'verify_link' => url('/customers/confirm') . '/' . $this->customer->hash
                        ]);

        if (env('MAIL_DRIVER') == 'sendgrid') {
            $message->sendgrid([
                'template_id' => env('SENDGRID_VERIFICATION_TPL'),
                'personalizations' => [
                    [
                        'dynamic_template_data' => [
                            'customer' => $this->customer->toArray(),
                            'verify_link' => url('/customers/confirm') . '/' . $this->customer->hash
                        ]
                    ]
                ]
            ]);
        }

        return $message;
    }
}
