<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferBranchLeadsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $branchfrom;
    public $branchto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Branch $branchfrom, Branch $branchto)
    {
        $this->branchfrom = $branchfrom;
        $this->branchto = $branchto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.branches.leadstransferred');
    }
}
