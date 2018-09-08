<?php

namespace App\Mail;

use App\Customer;
use Sichikawa\LaravelSendgridDriver\SendGrid;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetEmail extends Mailable
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
        return $this->view('emails.dummy')
                    ->sendgrid([
                        'template_id' => env('SENDGRID_PASSWORD_RESET_TPL'),
                        'personalizations' => [
                            [
                                'dynamic_template_data' => [
                                    'customer' => $this->customer->toArray(),
                                    'password_reset_link' => url('/password/reset') . '/' . $this->customer->hash
                                ]
                            ]
                        ]
                    ]);
    }
}
