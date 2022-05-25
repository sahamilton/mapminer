<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

use App\Address;
use App\User;
use App\Mail\TransferLeadRequest;

class TransferLeadRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $address;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Address $address, User $user)
    {
       $this->address= $address->load('claimedByBranch.manager');
       $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->address->claimedByBranch as $branch) {
            Mail::to([$branch->manager->first()->fullEmail()])
            ->cc([$this->user->person->fullEmail()])
            ->queue(new TransferLeadRequest($this->address, $this->user));
        }
    }
}
