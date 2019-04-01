<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    
    public function testAccountsView()
    {
         $this->browse(function ($first) {
            $first->loginAs(User::find(1))
            ->visit('/branches')
            ->waitForText('All Branches')
            ->assertSee('Map View')
            ->clickLink('Portland, OR')
            ->assertSee('Branch managed by')
            ->clickLink('List view')
            ->assertSee('Locations');
         });
    }
}
