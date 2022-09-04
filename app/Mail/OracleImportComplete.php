<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\OracleSource;

class OracleImportComplete extends Mailable
{
    use Queueable, SerializesModels;
    public $source;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OracleSource $source)
    {
        $this->source = $source->load('user.person');
        $this->_setType();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.oracle-import-complete');
    }

    private function _setType()
    {
        switch ($this->source->type) {

            case 'adds':

            $this->type = 'added';
            break;

            case 'refresh':
            $this->type = 'refreshed';
            break;

            case 'deletes':

            $this->type = 'deleted';
            break;
        }
    }
}
