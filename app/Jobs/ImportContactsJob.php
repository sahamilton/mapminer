<?php

namespace App\Jobs;

use App\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $contacts;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $contacts, User $user)
    {
        $this->contacts = $contacts;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->contacts as $contact) {
            $inserts[] = array_merge($contact, ['created_at'=>now(), 'user_id'=> $this->user->id]);
        }

        Contact::insert($inserts);
    }
}
