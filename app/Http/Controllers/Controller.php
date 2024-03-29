<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    // public function __construct(){
    //     $notification_data = offer_post::offer_notification();
    //     view()->share('notification_data', $notification_data); 
    // }
    public function response($obj, $msgSuccess, $msgError) {
        
        $response = new \stdClass();
        if($obj) {
                $response->new = $obj;
                $response->messageType = 'success';
                $response->message = $msgSuccess;               
        } else {
                $response->new = false;
                $response->messageType = 'danger';
                $response->message = $msgError;
        }
        return response()->json($response);
    }

    
}
