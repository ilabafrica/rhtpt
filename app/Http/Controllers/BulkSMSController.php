<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Libraries\AfricasTalkingGateway as Bulk;

use Config;

class BulkSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //  Prepare to send SMS
      // Retrieve login credentials
      $username   = Config::get('username');
      $apikey     = Config::get('api-key');
      //  TO DO: Use query to retrieve -- number to send messages
      $recipients = "+254711XXXYYY,+254733YYYZZZ";
      //  TO DO: Use query to retrieve -- Message entered in textarea
      $message    = "";
      // Create a new instance of Bulk SMS gateway.
      $sms    = new Bulk($username, $apikey);
      // use try-catch to filter any errors.
      try
      {
        // Send messages
        $results = $sms->sendMessage($recipients, $message);

        foreach($results as $result) {
          // status is either "Success" or "error message" and save.
          echo " Number: " .$result->number;
          echo " Status: " .$result->status;
          echo " MessageId: " .$result->messageId;
          echo " Cost: "   .$result->cost."\n";
        }
      }
      catch ( AfricasTalkingGatewayException $e )
      {
        echo "Encountered an error while sending: ".$e->getMessage();
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
