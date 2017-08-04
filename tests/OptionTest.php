<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Option;
class OptionTest extends TestCase
{
	use DatabaseMigrations;
	public function setup(){
		parent::Setup();
		$this->setVariables();
	}
	public function setVariables(){
    	$this->optionData=array(        
			"title"=>'Sample Title',
			"description"=>'Sample Description'
		);
    	$this->updatedOptionData=array(        
			"title"=>'Updated Title',
			"description"=>'Updated Description'
        );
	}
	public function testStoreOption()
	{
		$response=$this->json('POST', 'vueoptions',$this->optionData);
		$this->assertEquals(200,$response->getStatusCode());
		$this->assertArrayHasKey("subject",[$response->original]);
	}
	public function testListOption()
	{
		$response=$this->json('GET', '/vueoptions?page=1');
		$this->assertEquals(200,$response->getStatusCode());
		
	}
	public function testUpdateOption()
	{
		$this->json('POST', '/vueoptions',$this->updatedOptionData);
		$response=$this->json('PUT', '/vueoptions/1');
		$this->assertEquals(200,$response->getStatusCode());
		$this->assertArrayHasKey("subject",[$response->original]);
	}
	public function testDeleteOption()
	{
		$this->json('POST', '/vueoptions',$this->optionData);
		$response=$this->delete('/vueoptions/1');
		$this->assertEquals(200,$response->getStatusCode());		
	}
}