<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardTest extends TestCase
{
    /**
     * Home page test
     *
     * @return void
     */
    public function testBasicExample()
    {
    	$this->loginAdmin();
        $this->visit('/')
            ->see('')
            ->click('Dashboard')
            ->see('Home')
            ->visit('/home')
            ->see('Home');
    }
}