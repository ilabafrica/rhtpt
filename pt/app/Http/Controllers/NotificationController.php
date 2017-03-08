<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Input;

use App\Notification;

class NotificationController extends Controller
{
    //

    /**
     * Function to return types of notifications.
     *
     */
    public function fetch()
    {
        $response = [];
        $data = [
                    Notification::PANEL_DISPATCH => "Panel Dispatch", 
                    Notification::RESULTS_RECEIVED => "Results Received", 
                    Notification::FEEDBACK_RELEASE => "Feedback Release", 
                    Notification::OTHER => "Other"
                ];
        foreach($data as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return $response;
    }
    /**
     * Load template given the id.
     *
     */
    public function template($id)
    {
        $id = (int)$id;
        $template = Notification::where('template', $id)->first()->message;
        return $template;
    }
}
