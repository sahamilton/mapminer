<?php

namespace App\Jobs;

use App\Campaign;
use App\Mail\SendCampaignLaunchedMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendCampaignLaunched implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to([['email'=>$this->user->email, 'name'=>$this->user->person->fullName()]])

                ->send(new SendCampaignLaunchedMail($this->user, $this->campaign));
    }
}
