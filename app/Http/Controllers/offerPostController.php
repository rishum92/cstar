<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use App\offer_post;
use Redirect;
use Auth;
use Mail;
use DateTimeZone;
use Carbon;


class offerPostController extends Controller
{
    //insert the post
    public function offer(Request $Request)
    {
      
      $currency     = $Request->input('currency');
      $rate         = $Request->input('offerrate');
      $detail       = $Request->input('offerdetails');
      $user_id      =  Auth::user()->id;
      $user_name    =  Auth::user()->username;
      $user_img     =  Auth::user()->img;

      $ip = request()->ip();
    
      $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
      $ipInfo = json_decode($ipInfo);
      $timezone = $ipInfo->timezone;
      date_default_timezone_set($timezone);
      date_default_timezone_get();
      $date = date('Y/m/d H:i:s');

      offer_post::addPost($rate,$detail,$user_id,$user_name,$user_img,$currency,$date);
      return redirect('offers')->with('message', 'Your Post is Successfully Added.')->with('messageType', 'success');
    }
    
    //fetch the posts
    public function getData()
    {
       $post_data = offer_post::getPost();
       return view('offers',['offerpost'=>$post_data]);
    }

    //fetch the post using ajax
    public function ajxfetchData(Request $request)
    {
       if($request->ajax())
       {
        $data = offer_post::getPost();
        $user_posts = offer_post::get_user_posts();
        return view('offer_load',['offerpost'=>$data,'user_posts'=>$user_posts])->render();
       }
       
    }

    //fetch my offer interest ajax
    public function upper_pagination(Request $request)
    {
       if($request->ajax())
       {
        
        $myofferpost = offer_post::myofferPost();
        return view('my_offers_interested_user',['myofferpost'=>$myofferpost])->render();
       }
       
    }


    //either intrested or note
    public function interested(Request $Request)
    { 
        //for email
        $user_id        = $Request->user_id;
        $user  = offer_post::get_user_data($user_id);
        $user_name      = $user->username;
        $user_email     = $user->email;//for email
        $emailMessage = $user_name;


        $post_id       = $Request->id;
        $intrested_id  = Auth::user()->id;
        $check_result  = offer_post::checkStatus($post_id, $intrested_id);
        //echo "<pre>";print_r($check_result);die;
        if(empty($check_result))
        {
          $count_points = offer_post::interest_user($post_id, $intrested_id, $user_id);

          if($count_points >= 1000)
          {
            $fromEmail = 'casualstar.uk.info@gmail.com'; 
            $toEmail = 'manifest.pankaj.k@gmail.com';

            Mail::send('emails.pgp', ['emailMessage' => $emailMessage], function($email) use( $fromEmail, $toEmail) {
                $email->from($fromEmail)->to($toEmail)->subject('CasualStar: New message');
            });
          }               
          
          return redirect('offers')->with('message', 'Your interested to this post')->with('messageType', 'success');
        } 
        else
        {
         return redirect('offers')->with('message', 'You have already shown interest for this post.')->with('messageType', 'success');
        }
    } 

    //post intrested users
    public function post_intrest_users(Request $Request)
    {
      $post_data = offer_post::getPost();
      $post_id = $Request->id;
      $interested_users = offer_post::post_intrest_users($post_id);
      return view('interested_users',['interested_users'=>$interested_users]);
    }

    //myoffer interested users
     public function myoffer_intrest_users(Request $Request)
    {
      $post_data = offer_post::getPost();
      $post_id       = $Request->id;
      $myoffer_interested_users = offer_post::post_intrest_users($post_id);
      //echo "<pre>";print_r($myoffer_interested_users);die;
      return view('myoffer_interest_users',['myoffer_interested_users'=>$myoffer_interested_users]);
    }

    //logged interested
    public function logged_interested(Request $Request)
    { 
      $post_data = offer_post::getPost();
      $post_id = $Request->id;
      $my_logged_interested_users = offer_post::post_intrest_users($post_id);
      //echo "<pre>";print_r($myoffer_interested_users);die;
      return view('logged_interested',['my_logged_interested_users'=>$my_logged_interested_users]);
    }

    //delete posts
    public function deletePost(Request $Request)
    {
        $post_id = $Request->id;
        offer_post::deleteOffers($post_id);
        return redirect('offers')->with('message', 'Offer delete successfully')->with('messageType', 'success');
    }
    public function deletemyoffer(Request $Request)
    {
      $post_id = $Request->id;
      offer_post::deletemyOffer($post_id);
      return redirect('offers')->with('message', 'My offer delete successfully')->with('messageType', 'success');
    }
    public function delete_logged_interest(Request $Request)
    {
      $post_id = $Request->id;
      offer_post::delete_logged_interest($post_id);
      return redirect('offers')->with('message', 'Logged interest delete successfully')->with('messageType', 'success');
    }

    public function send_offer_message(Request $Request)
    {
      $post_data = $Request->all();
      $post_id   =  $Request->post_id;
      $receiver_id   =  $Request->receiver_id;
      $offer_message   =  $Request->offer_message;
      $sender_id = $Request->sender_id;

      $post_data['sender_id']  = Auth::user()->id;
      offer_post::send_offer_message($post_data);

      return redirect('offers')->with('message', 'Message send successfully')->with('messageType', 'success');
    }

    //pgp activation
    public function pgp_activation()
    {
      offer_post::pgp_activation();

      return redirect('activity')->with('message', 'Process is successfully activated for 24 hrs')->with('messageType', 'success');           
    }
}
