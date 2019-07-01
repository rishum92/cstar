 <div class="col-md-12">
          @foreach($offerpost as $offers)
             <div class="col-md-6 offer_container_grid">
              
                @if(in_array($offers->post_id, $user_posts))
                  <div class="offer_content_shadow2  offer_cont_shadow">
                @else
                  <div class="offer_cont_shadow offer_content_shadow_default">
                @endif 

                    <div class="offer_left">
                      <a href="{{url('users/'.$offers->username)}}">
                        @if($offers->img == '')
                        <img src="img/male.jpg" class="offer_pro_pic" />
                        @else
                        <img src="img/users/{{$offers->username}}/previews/{{$offers->img}}" class="offer_pro_pic" />
                        @endif
                      </a>
                      <h3>
                        <a href="{{url('users/'.$offers->username)}}"><span>{{$offers->username}}</span>
                        </a>
                      </h3>
                    </div>
                    <div class="offer_right">
                      <h3><span>{{$offers->currency}}{{$offers->offer_rate}}</span></h3>
                    </div> 
                    <div class="offer_detailes_box">
                      <p>
                        {{$offers->offer_details}}
                      </p>
                    </div>
                    <br/>
                    @if(Auth::user()->gender == 'male')
                       <button type="button" disabled class="btn btn-default btn_interested">
                         <i class="fa fa-thumbs-up"></i> Interested
                       </button>
                    @else

                      @if($offers->intrest_count < 10)
                        <a href="{{url('interested/'.$offers->post_id. '/' . $offers->id)}}">
                          <button type="submit" class="btn btn-default btn_interested">
                          <i class="fa fa-thumbs-up" ></i> Interested
                          </button>
                        </a>
                      @else
                       <a href="#">
                       <button type="submit" disabled class="btn btn-default btn_interested">
                         <i class="fa fa-thumbs-up"></i> Interested
                       </button>
                       </a>
                      @endif
                    @endif
                 
                    <!-- popup call -->
                      <span class="viewOffers cursor-pointer" onclick="interestedcount(<?php echo $offers->post_id; ?>)">{{$offers->intrest_count}}</span>
                    <!-- pop call close -->  
                      
                      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.js"></script>
                      @if($offers->intrest_count < 10)
                        <span class="post_remaining" id="clock{{$offers->post_id}}"></span>
                        <span class="post_remaining">
                        <?php 
                        
                          //$ip = $_SERVER['REMOTE_ADDR'];
                          $current_post_ip = $offers->current_post_ip;
                          $ipInfo = file_get_contents('http://ip-api.com/json/' . $current_post_ip);
                          $ipInfo = json_decode($ipInfo);
                          $timezone = $ipInfo->timezone;
                          date_default_timezone_set($timezone);
                          date_default_timezone_get();
                          
                          $date_a = new DateTime(date("F j, Y, H:i:s"));
                          $date_b = new DateTime($offers->offer_post_date);
                          $interval = date_diff($date_a,$date_b);
                          $stop_date = date('Y-m-d H:i:s', strtotime($offers->offer_post_date . ' +1 day'));
        
                        
                         
                         
                          $interval_days = $interval->format('%a');
                          if ($interval_days > 0) 
                          {
                            echo '<font style="color:red;">Closed</font>';
                            // echo "<script>$('#interested_btn{{$offers->post_id}}').attr('disabled','true'); </script>";
                          }
                          else
                          {
                        ?>
                          <script type="text/javascript">
                          $('#clock{{$offers->post_id}}').countdown("{{$stop_date}}", function(event) 
                          { 
                            
                            var totalHours = event.offset.totalDays * 24 + event.offset.hours;
                            var totalMins = event.strftime('%M');
                            var totalSecs = event.strftime('%S');
                            if(totalHours < 12)
                            {
                             
                              $(this).css("color", "red").html();
                            }
                            if(totalHours==0 && totalMins==0 && totalSecs==0)
                            {
                              $(this).html('<font style="color:red;">Closed</font>');
                            }
                            else {
                              $(this).html(event.strftime(totalHours + ' : %M : %S Remaining'));
                            }
                          });
                        </script>
                        <?php
                          }  
                        ?>
                        </span>
                        
                      @else
                       <span class="post_closed">Closed</span>
                      @endif 
                      <br/><br/>
                    <span class="offer_id">Id: #{{$offers->post_id}}</span>
                     @if($offers->user_id == Auth::user()->id OR Auth::user()->username == 'Admin')
                      <a onclick = "delete_confirmation({{$offers->post_id}})"><span class="fa fa-trash offer_delete"></span></a>
                     @endif 
                  </div> 
                </div>
               <!--Delete Confirmation Start-->
               <script>
              function delete_confirmation(post_id) {
               $('#delete_confirmation_popup').modal('show');
               $('#post_id').val(post_id);
              }
              </script>
              <!--Delete Confirmation Popup Close-->
              <div class="modal fade" id="delete_confirmation_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete this post?
                    </div>
                    <input type="hidden" name="post_id" id="post_id">
                    <div class="modal-footer">
                      <button class="btn btn-primary" data-dismiss="modal" id="confirm_delete">Yes</button>
                      <button class="btn btn-secondary" data-dismiss="modal">No</button>
                    </div>
                  </div>
                </div>
              </div>
            <!--Delete Confirmation Popup End-->
            <!--Delete Confirmation PopupAjax Start-->
            <script>
              $("#confirm_delete").click(function() {
                var post_id = $("#post_id").val();
                  $.ajax({
                    type: 'GET',
                    url: 'delete/'+post_id,
                    success:function(data)
                    {
                      location.reload();
                    }
                  });
                });
            </script>
            <!--Delete Confirmation PopupAjax Close-->

              <!-- popup  Modal start -->
              <div class="modal fade" id="popupmodal" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Interested Users</h4>
                    </div>
                    <div class="modal-body" id="interested_model_id"></div>
                    <div class="modal-footer">
                      <button type="button" id="refreshdata" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  
                </div>
              </div>
              <!-- popup modal close--> 
              
              @endforeach
         
            
            <!-- pagination start -->
            <div class="col-md-12">
             <center id="offer_pagination"> {{ $offerpost->links() }} </center>
            </div> 
            <!-- pagination close -->
            
             <div class="col-md-12">
               <div class="footer_data">
                <p>
                  <strong>NOTE :</strong> 
                   Generous subs earn points for their Offers and our way of encouraging more of that generous behaviour is to reward them with 1 day of free Private Gallery Access (PGa) throughout the website. This is only done once a sub earns enough Private Gallery Points (PGP).
                 </p>
               </div>
             </div>
             
         </div>
       </div>