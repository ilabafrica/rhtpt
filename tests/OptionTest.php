<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Option;

class OptionTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * Variables for use in the tests
     *
     * @return void
     */
    public function setup()
    {
        parent::Setup();
        $this->setVariables();
    }
    public function setVariables()
    {
        $this->optionData=array(
        
            "title" => "Sample Title",
            "description" => "Sample Description",
        );
        $this->updatedOptionData=array(
        
            "title" => "Updated Title",
            "description" => "Updated Description",
        );
    }
    /**
     * Test index options
     *
     * @return void
     */
    public function testIndex()
    {
        $this->loginAdmin();
        $this->visit('/vueoptions?page=1')
            ->see('First Response');
    }
    /**
     * Test create option
     *
     * @return void
     */
    /*public function testCreatePost()
    {
        $this->loginAdmin();
        $response = $this->json('POST', '/vueoptions/create',$this->optionData);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey("subject",[$response->original]);
    }*/
    /**
     * Test show/edit option
     *
     * @return void
     */
    /*public function testEditOption()
    {
        $this->loginAdmin();
        $this->visit('/blog/1/edit')
            ->type('My summary', 'summary')
            ->type('Tag5', 'tags')
            ->press('Send')
            ->visit('/blog/post-1')
            ->see('My summary')
            ->see('Tag5');
    }*/
}
