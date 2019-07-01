<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use DB;
use Auth;
use File;

class competition_user extends Model
{
  
  //fetch the user data
  public static function getdata()
  {
    $getuserdata  = DB::table('competition_interested_users')
                    ->select('users.*','competiton_vote.*','competition_interested_users.*', DB::raw('count(competiton_vote.is_vote) as total_votes'))
                    ->join('users','id','=','competition_interested_users.user_id')
                    ->leftJoin('competiton_vote', 'users.id', '=', 'competiton_vote.user_id')
                    ->groupBy('users.id', 'competiton_vote.user_id')
                    ->orderBy('total_votes','DESC')
                    ->where('is_status',1)
                    ->paginate(20);
    //echo "<pre>";print_r($getuserdata);die;
    //vote count of each post
     foreach ($getuserdata as $key => $uservote) {
      $vote_counts  = DB::table('competiton_vote')
                      ->select('is_vote')
                      ->where('competiton_vote.competition_id', $uservote->competition_id)
                      ->where('is_vote','=',1)
                      ->get(); 
      $getuserdata[$key]->total_votes = count($vote_counts);
    }
    //Add new user
    foreach ($getuserdata as $key => $position) {
      if($position->user_position == '')
      {
        $position_user  = DB::table('competiton_vote')
                          ->select('user_position')
                          ->orderBy('user_position','DESC')
                          ->first();
        
        if(empty($position_user)){
          $insert_users =  DB::table('competiton_vote')
                        ->insert(['is_vote'=>0,'competition_id'=>$position->competition_id,'user_id'=>$position->user_id,'user_position'=>1]);
        }
        else{
          $positionofuser = $position_user->user_position;
          if($positionofuser == $position_user->user_position)
          {
            $positionofuser++;
          }
          $insert_users =  DB::table('competiton_vote')
                        ->insert(['is_vote'=>0,'competition_id'=>$position->competition_id,'user_id'=>$position->user_id,'user_position'=>$positionofuser]);
        }
      }
    }
    //comment count of each post
    foreach ($getuserdata as $key => $userdata) {
      $comment_count  = DB::table('profile_comments')
                        ->select('comment')
                        ->where('competition_id', $userdata->competition_id)
                        ->where('is_deleted',0)
                        ->get(); 
      $getuserdata[$key]->total_comment = count($comment_count);
    }
    //vote prize of each post
    foreach ($getuserdata as $key => $voteprize) {
      $voteprize  = DB::table('competiton_vote')
                    ->select('vote_amount')
                    ->where('competition_id', $voteprize->competition_id)
                    ->first(); 
      $getuserdata[$key]->vote_prize = $voteprize;
    }
    return $getuserdata;
  }
    
    
  //exist user
  public static function existuser($user_id)
  {
    $exist  =   DB::table('competition_interested_users')
                ->join('users','id','=','competition_interested_users.user_id')
                ->where('competition_interested_users.user_id','=',$user_id)
                ->where('is_status',1)
                ->get();
    //echo "<pre>";print_r($exist);die;
    return $exist;
  }

  //update the competition expiry date
  public static function updateexpirydate($date,$user_id)
  {
    $insertdate     =   DB::table('competition_expiry_date')
                        ->update(['user_id'=>$user_id,'ExpiryDate'=>$date]);
    return $insertdate;
  }


  //add vote
  public static function confirm_vote($confirm_vote,$voter_id,$competition_userid,$competitionid)
  {
    $insertvote = DB::table('competiton_vote')
                  ->insert(['voter_id'=>$voter_id,'is_vote'=>$confirm_vote,'user_id'=>$competition_userid,'competition_id'=>$competitionid]);
    
    $getalluserdata = DB::table('competition_interested_users')
                      ->select('users.*','competiton_vote.*','competition_interested_users.*', DB::raw('count(competiton_vote.is_vote) as total_votes'))
                      ->join('users','id','=','competition_interested_users.user_id')
                      ->leftJoin('competiton_vote', 'users.id', '=', 'competiton_vote.user_id')
                      ->groupBy('users.id', 'competiton_vote.user_id')
                      ->orderBy('total_votes', 'desc')
                      ->where('is_status',1)
                      ->paginate(20);
                      
    $vote_rank  = 0; 
    $temp_vote  = 0;
    foreach ($getalluserdata as $key => $getuseridforposition) {
      if($temp_vote != $getuseridforposition->total_votes)
      {
        $vote_rank++;
        $temp_vote = $getuseridforposition->total_votes;
      }
      $updatevotepositions  = DB::table('competiton_vote')
                              ->where('competition_id',$getuseridforposition->competition_id)
                              ->update(['user_position'=>$vote_rank]);
    }
    return $getalluserdata;
  }

  //count voter
  public static function vote_count($voter_id)
  {
    $votes  =   DB::table('competiton_vote')
                ->where('voter_id',$voter_id)
                ->get();
    foreach ($votes as $key => $vote) {
      $votes[$key] = $vote->user_id;
    }
    return $votes;
  }
  
  //exist voters
  public static function exist_voters($voter_id)
  {
    $existvoterid   =   DB::table('competiton_vote')
                        ->select('user_id')
                        ->where('voter_id',$voter_id)
                        ->get();
   
    return $existvoterid;
  }

  //show competition expiry date
  public static function showdate()
  {
    $showdate   =   DB::table('competition_expiry_date')
                      ->select('ExpiryDate')
                      ->get();
    return $showdate;  
  }

  //update the terms and condition
  public static function termscondition($user_id,$termscondition)
  {
    $termscondition = DB::table('competiton_terms_condition')
                   ->update(['user_id'=>$user_id,'termscondition'=>$termscondition]);
    return $termscondition;
  }

  //show the terms and condition
  public static function showtermscondition()
  {
    $termscondtion = DB::table('competiton_terms_condition')
                  ->select('termscondition')
                  ->get();
    return $termscondtion[0]->termscondition;
  }

  //delete the competition account and folder of user
  public static function competitiondelete($competitionid) 
  { 
    $username = DB::table('competition_interested_users')
              ->select('username')
              ->where('competition_id', '=', $competitionid)
              ->get();
    $user_name = $username[0]->username;
              DB::table('competition_interested_users')
              ->where('competition_id', '=', $competitionid)
              ->delete();
    if(file_exists('img/competition_user/' .$user_name)) {
      File::deleteDirectory(public_path('img/competition_user/'.$user_name));
    }
  }

  //delete vote
 public static function votedelete($competitionid)
  { 
    $delete_vote =  DB::table('competiton_vote')
                    ->where('competition_id',$competitionid)
                    ->delete();

    $getalluserdata = DB::table('competition_interested_users')
                      ->select('users.*','competiton_vote.*','competition_interested_users.*', DB::raw('count(competiton_vote.is_vote) as total_votes'))
                      ->join('users','id','=','competition_interested_users.user_id')
                      ->leftJoin('competiton_vote', 'users.id', '=', 'competiton_vote.user_id')
                      ->groupBy('users.id', 'competiton_vote.user_id')
                      ->orderBy('total_votes', 'desc')
                      ->where('is_status',1)
                      ->paginate(20);
                      
    $vote_rank  = 0; 
    $temp_vote  = 0;
    foreach ($getalluserdata as $key => $getuseridforposition) {
      if($temp_vote != $getuseridforposition->total_votes)
      {
        $vote_rank++;
        $temp_vote = $getuseridforposition->total_votes;
      }
      $updatevotepositions  = DB::table('competiton_vote')
                              ->where('competition_id',$getuseridforposition->competition_id)
                              ->update(['user_position'=>$vote_rank]);
    } 
    
    return $getalluserdata;
    
  }

  //delete comment
  public static function commentdelete($competitionid)
  {
    $deletecomment  = DB::table('profile_comments')
                    ->where('competition_id','=',$competitionid)
                    ->delete();
  } 

  //update the vote when the account is deleted  
  public static function update_vote($user)
  {
    $update_vote  = DB::table('competiton_vote')
                  ->where('voter_id',$user)
                  ->update(['is_vote'=>0]);
  }

  //delete the competition account
  public static function delete_account($user)
  {
    $delete_account = DB::table('competition_interested_users')
                    -> where('user_id',$user)
                    ->update(['is_status'=>0]);
  }

  //update vote amount for first place
  public static function update_amount_edit($vote_amount,$user_id)
  {
    $update_amount  = DB::table('competiton_vote')
                    -> where('user_id',$user_id)
                    ->update(['vote_amount'=>$vote_amount]);
  }
  //update vote amount for second place
  public static function update_second_place_amount_edit($vote_amount,$user_id)
  {
    $update_amount  = DB::table('competiton_vote')
                    -> where('user_id',$user_id)
                    ->update(['vote_amount'=>$vote_amount]);
  }

  //update vote amount for third place
  public static function update_third_place_amount_edit($vote_amount,$user_id)
  {
    $update_amount  = DB::table('competiton_vote')
                    -> where('user_id',$user_id)
                    ->update(['vote_amount'=>$vote_amount]);
  }
  //select vote amount
  public static function select_vote_amount($user_id)
  {
    $get_amount   = DB::table('competiton_vote')
                  ->select('vote_amount')
                  -> where('user_id',$user_id)
                  ->first();
    return $get_amount;
  }

  //update title
  public static function update_title ($edit_title,$user_id)
  {
    $insert_title   = DB::table('competition_expiry_date')
                    ->where('user_id',$user_id)
                    ->update(['competition_title'=>$edit_title]);
  }

  //fetch the title
  public static function get_title()
  {
    $get_title  = DB::table('competition_expiry_date')
                ->select('competition_title')
                ->get();
    return $get_title[0]->competition_title;
  }

  //get comment user data
  public static function comment_user_data($user_id)
  {
    $user_data    = DB::table('competition_interested_users')
                  ->select('users.*','competition_interested_users.*')
                  ->join('users','users.id','=','competition_interested_users.user_id')
                  ->where('user_id', '=', $user_id)
                  ->get();
    return $user_data ;
  }

  //add comment
  public static function confirm_comment($comment,$competition_user_id,$competition_id,$user_id)
  {
    $insert_comment = DB::table('profile_comments')
                    ->insert(['competition_id'=>$competition_id,'user_id'=>$competition_user_id,'sender_id'=>$user_id,'comment'=>$comment]);
    $get_comment    = DB::table('profile_comments')
                    ->select('profile_comments.*','users.img','users.username')
                    ->join('users','users.id','=','profile_comments.sender_id')
                    ->where('profile_comments.user_id',$competition_user_id)
                    ->where('is_deleted',0)
                    ->orderBy('created_at','desc')
                    ->get();
      foreach ($get_comment as $key => $commentdata) 
      {
        $commentcount   = DB::table('profile_comments')
                        ->select('comment')
                        ->where('competition_id',$commentdata->competition_id)
                        ->where('is_deleted',0)
                        ->get();
        $get_comment[$key]->total_comment = count($commentcount);
      }
      return $get_comment;
  }

  //fetch comment
  public static function getcomments($user_id)
  {
    $getcomments  = DB::table('profile_comments')
                  ->select('profile_comments.*','users.username','users.img' )
                  ->join('users','users.id','=','profile_comments.sender_id')
                  ->where('profile_comments.user_id',$user_id)
                  ->where('is_deleted',0)
                  ->orderBy('created_at','desc')
                  ->get();
    return $getcomments;
  }

  //delete comment
  public static function deletecomment($comment_id)
  {
    $delete_comment = DB::table('profile_comments')
                    ->where('id',$comment_id)
                    ->update(['is_deleted'=>1]);
  }

  //comment count
  public static function comment_count($competitionid)
  {
    $comment_count  = DB::table('profile_comments')
                    ->select(DB::raw('count(comment) as total_votes'))
                    ->where('competition_id',$competitionid)
                    ->get();
    return $comment_count;
  }
  public static function delete_all_competition()
  {
    $delete_all_competition = DB::table('competition_interested_users')
                            ->delete();
  }
  public static function delete_all_competition_votes()
  {
    $delete_all_competition_votes = DB::table('competiton_vote')
                                  ->delete();
  }
  public static function delete_all_competition_comment()
  {
    $delete_all_competition_comment = DB::table('profile_comments')
                                    ->delete();
  }
}