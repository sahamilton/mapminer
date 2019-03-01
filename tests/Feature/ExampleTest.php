<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
       
        $this->browse(function ($first) {
            $first->loginAs(User::find(1))
              ->visit('/home')
              ->waitForText('National Account')
              ->assertSee('Account Views');
        });
    }
}
