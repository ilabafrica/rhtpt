<?php
abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://127.0.0.1:8000';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
    /**
     *  Log administrator
     *
     * @return void
     */
    public function loginAdmin()
    {
        Session::start();
        $response = $this->call('POST', '/login', [
            'username' => 'admin',
            'password' => 'password',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(302, $response->getStatusCode());
        // $this->assertEquals('auth.login', $response->original->name());
    }
    /**
     * @test
     */
    public function loginWithWrongCredentials()
    {
        $this->visit('/')
            ->see('Login')
            ->type('wrong', 'username')
            ->type('wrong', 'password')
            ->press('signin')
            ->seePageIs('/login')
            ->see('These credentials do not match our records');
    }
}