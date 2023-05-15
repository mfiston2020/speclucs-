<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerInvoice;
    public $client;
    public $company;
    public $tot;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerInvoice,$client,$company,$tot)
    {
        $this->customerInvoice  =   $customerInvoice;
        $this->client  =   $client;
        $this->company  =   $company;
        $this->tot  =   $tot;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('MyOptics Invoice')
                    ->view('manager.customEmail.customerinvoice');
    }
}
