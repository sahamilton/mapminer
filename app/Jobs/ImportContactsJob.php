<?php

namespace App\Jobs;

use App\Models\ContactImport;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class ImportContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
       
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contacts = ContactImport::query()
            ->select('fullname', 'firstname', 'lastname', 'email', 'title', 'contactphone', 'address_id')
            ->whereNotNull('address_id')
            ->get()
            ->toArray();
        if (count($contacts) >0) {
            foreach ($contacts as $contact) {
                
                $inserts[] = array_merge($contact, ['created_at'=>now(), 'user_id'=> $this->user->id]);
               
            }
            Contact::insert($inserts);
        }
       
    }
}
