        @foreach($myofferpost as $myoffer)
            <div class="col-md-6 offer_container_grid">
              <div class="offer_cont_shadow">
                <div class="offer_left">
                  <a href="{{url('users/'.Auth::user()->username)}}">
                    @if($myoffer->img == '')
                      <img src="img/male.jpg" class="offer_pro_pic" />
                    @else
                      <img src="{{'img/users/'.Auth::user()->username.'/previews/'.Auth::user()->img}}" class="offer_pro_pic" />
                    @endif
                  </a>
                  <h3>
                    <a href="{{url('users/'.Auth::user()->username)}}"><span>{{Auth::user()->username}}</span>
                    </a>
                  </h3>
                </div>
                <div class="offer_right">
                  <h3><span>{{$myoffer->currency}}{{$myoffer->offer_rate}}</span></h3>
                </div> 
                <div class="offer_detailes_box">
                  <p>
                    {{$myoffer->offer_details}}
                  </p>
                </div>
                <br/>
                <button type="button" disabled class="btn btn-default btn_interested">
                       <i class="fa fa-thumbs-up"></i> Interested
                </button>
                <span class="viewOffers cursor-pointer" onclick="myofferinterestedcount(<?php echo $myoffer->id; ?>)">
                    {{$myoffer->intrest_count}}
                </span>
                <span class="post_remaining" id="clockbottom{{$myoffer->id}}"></span>
                <span class="post_remaining">
                <?php 
                    $date_a = new DateTime(date("F j, Y, H:i:s"));
                    $date_b = new DateTime($myoffer->created_at);
                    $interval = date_diff($date_a,$date_b);
                    $stop_date = date('Y-m-d H:i:s', strtotime($myoffer->created_at . ' +1 day'));
                
                    $interval_days = $interval->format('%a');
                    if ($interval_days > 0) 
                    {
                        echo '<font style="color:red;">Closed</font>';
                    }
                  else
                  {
                ?>
                  <script type="text/javascript">
                  $('#clockbottom{{$myoffer->id}}').countdown("{{$stop_date}}", function(event) 
                  { 
                      var totalHours = event.offset.totalDays * 24 + event.offset.hours;
                      var totalMins = event.strftime('%M');
                      var totalSecs = event.strftime('%S');
                      if(totalHours < 12)
                      {
                        console.log('greet12');
                        $(this).css("color", "red").html();
                      }
                      if(totalHours==0 && totalMins==0 && totalSecs==0)
                        $(this).html('<font style="color:red;">Closed</font>');
                      else
                        $(this).html(event.strftime(totalHours + ' : %M : %S Remaining'));
                  });
                  </script>
                <?php
                  }  
                ?>
                </span>
                <br/><br/>
                <span class="offer_id">Id: #{{$myoffer->id}}</span>
                <div class="post_delete_date">
                  <a href="#">
                    <span >
                      <?php
                        // echo date("H:i ",strtotime($myoffer->created_at));
                        echo date("d/m/Y", strtotime($myoffer->created_at) ); ?>
                    </span>
                  </a> &nbsp;
                  <a onclick = "delete_myoffer({{$myoffer->id}})">
                    <span><i class="fa fa-trash" style="cursor: pointer;"></i></span>
                  </a>
                </div>
              </div>
            </div>
            <!--Delete Myoffer Confirmation Start-->
              <script>
              function delete_myoffer(id) {
               $('#delete_myoffer_popup').modal('show');
               $('#offer_id').val(id);
              }
              </script>
              <!--Delete Myoffer Confirmation Popup Start-->
              <div class="modal fade" id="delete_myoffer_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete this offer?
                    </div>
                    <input type="hidden" name="offer_id" id="offer_id">
                    <div class="modal-footer">
                      <button class="btn btn-primary" data-dismiss="modal" id="confirm_offer">Yes</button>
                      <button class="btn btn-secondary" data-dismiss="modal">No</button>
                    </div>
                  </div>
                </div>
              </div>
            <!--Delete Myoffer Confirmation Popup End-->
            <!--Delete Myoffer Confirmation PopupAjax Start-->
            <script>
              $("#confirm_offer").click(function() {
                var offer_id = $("#offer_id").val();
                  $.ajax({
                  type: 'GET',
                  url: 'deletemyoffer/'+offer_id,
                  success:function(data)
                  {
                    location.reload();
                  }
                });
              });
            </script>
            <!--Delete Myoffer Confirmation PopupAjax Close-->
            <!--Delete Myoffer Confirmation Close-->


            @endforeach


            <div class="col-md-12" >
                <center id="my_offer_pagination"> {{ $myofferpost->links() }} </center>
            </div>

            <!-- popup my offer post start -->
            <div class="modal fade" id="myofferpost" role="dialog">
              <div class="modal-dialog">
              <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">My offer post</h4>
                    </div>
                    <div class="modal-body" id="myoffer_interested_model_id">
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="refreshdata" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!-- popup my offer post close -->