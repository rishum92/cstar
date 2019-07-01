<?php //echo "<pre>";print_r($notification_data);die; ?>


<?php $__env->startSection('style'); ?>
  <link href="<?php echo e(URL::asset('css/supersubs.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta'); ?>
  <title>Super Subs » CasualStar</title> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

 <?php if(Auth::user()->gender == 'male'): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>  

 <!-- mal start -->
<div data-ng-controller="SuperSubsController">
    <div class="wrap">
      <div class="highlight">
       <center> Post an Offer with a generous amount for any relevant desire you may have for a female to fulfil. Interest you receive for your Offers can eventually gain you access to ALL Private Galleries.</center>
       <hr/>
       <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">
                    <form method="post" action="<?php echo e(url('offerpost')); ?>" autocomplete="off">
               <!-- <div class="usd_offer">USD</div>  -->
                        <select class="form-control" name="currency" required>
                           <option value="">Select Currency</option>
                           <option value="A$">AUD</option>
                           <option value="BHD">BHD</option>
                           <option value="C$">CAD</option>
                           <option value="¥">CNH</option>
                           <option value="SFr.">CHF</option>
                           <option value="€">EUR</option>
                           <option value="£">GBP</option>
                           <option value="円">JPY</option>
                           <option value="JOD">JOD</option>
                           <option value="KYD">KYD</option>
                           <option value="KD">KWD</option>
                           <option value="NZD">NZD</option>
                           <option value="OMR">OMR</option>
                           <option value="kr">SEK</option>
                           <option value="$">USD</option>
                           <option value="R">ZAR</option>
                         </select>
           </div>
           <div class="col-md-3">
              
             <div class="form-group">
               <?php echo e(csrf_field()); ?>

               <input type="hidden" name="hiddenname" id="hiddenid">
               <input type="text" id="myField"  maxlength="8"  pattern="^[\d,]+$"  class="form-control" required="required" name="offerrate" placeholder="1,054"/>
             </div>

            </div>
            <div class="col-md-5">
              <div class="form-group ">
                <input type="text" class="form-control" maxlength="100" minlength="10" required="required" name="offerdetails"  placeholder="I would like to help with your bills."/>
                <p class="info_icon">
                <a href="#" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<div style='width:180px;'>Use this box to briefly detail your desire for which you want a Femdom to fulfil. Examples: I desire a custom video of female in shower. Or, I wish to be owned. Or, I would like to help with your bills.etc</div>">
                  <i class="fa fa-info-circle"></i>
                </a>
                <script>
                  $(document).ready(function(){
                  $('[data-toggle="tooltip"]').tooltip();   
                  });
                </script>
                </p>
              </div>
            </div>
            <div class="col-md-2">
             <div>
               <div class="form-group">
                 <button type="submit" class="btn btn-default btn_offer"><i class="fa fa-paper-plane"></i> Post</button>
               </div>
             </div>
           </div>
         </div>
      </div>
    </div>
  </div>
  
  <!-- male end -->
  <?php else: ?>
   <!-- femal start -->
    <div data-ng-controller="SuperSubsController">
    <div class="wrap">
      <div class="highlight">
       <center>Click the interested button to let the ad poster know you’re interested. If they wish to, they will contact you to discuss your offer in more detail if necessary. Only the first 10 interests will be logged for each Offer.</center>
     </div>
  </div>
   <!-- femal end -->
    <?php endif; ?>
    <div class="wrap">
       <div class="row"  id="table_data">
         <?php echo $__env->make('offer_load', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
       <br/>
      
      <?php if(Auth::user()->gender == 'male'): ?>
       <?php  //echo "<pre>";print_r($myofferpost);die;  ?>
       <!-- my offer post start -->

       <div class="row">
          <h1 class="my_offer_post">My offer post</h1>
          <?php if(empty($myofferpost->all())): ?>
            <p class="female_subpage_title">
              You currently have no closed or active Offers, Click Here (link to offers page) to Post an Offer today. The more interests you get, the faster you will achieve the PGa badge for your 24 hours access to ALL private galleries.
            </p>
          <?php elseif(Auth::user()->gender == 'female'): ?>
            <p class="female_subpage_title">
              Below are the Offers that you have shown interest in.
            </p>
          <?php endif; ?>
           <div class="col-md-12" id="my_offer_interested_users">

            <!-- offer intereste user -->
            <?php echo $__env->make('my_offers_interested_user', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- offer interested user close -->

            

          </div>
        </div>
       <!-- my offer post close -->
      <?php else: ?>
       <?php // echo "<pre>";print_r($myofferpostinterested->all());die; ?>
        <div class="row">
          <h1 class="my_offer_post">Logged interests</h1>
          <?php if(empty($myofferpostinterested->all())): ?>
            <p class="female_subpage_title">
              Sorry, you currently have no logged interests.
            </p>
          <?php else: ?>
            <p class="female_subpage_title">
              Below are the Offers that you have shown interest in.
            </p>
          <?php endif; ?>
        <div class="col-md-12">
          <?php //echo "<pre>";print_r($myofferpostinterested);die; ?>
          <?php foreach($myofferpostinterested as $interested): ?>
          <div class="col-md-6 offer_container_grid">
            <div class="offer_cont_shadow">
                <div class="offer_left">
                    <?php if($interested->img == ''): ?>
                    <a href="<?php echo e(url('users/'.$interested->username)); ?>">
                      <img src="img/male.jpg" class="offer_pro_pic" />
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(url('users/'.$interested->username)); ?>">
                      <img src="img/users/<?php echo e($interested->username); ?>/previews/<?php echo e($interested->img); ?>" class="offer_pro_pic" />
                    </a>
                    <?php endif; ?>
                    <h3>
                      <a href="<?php echo e(url('users/'.$interested->username)); ?>">
                        <span><?php echo e($interested->username); ?></span>
                      </a>
                    </h3>
                </div>
                <div class="offer_right">
                  <h3><span><?php echo e($interested->currency); ?><?php echo e($interested->offer_rate); ?></span></h3>
                </div> 
                <div class="offer_detailes_box">
                  <p>
                    <?php echo e($interested->offer_details); ?>

                  </p>
                </div>
                <br/>
                <button type="button" disabled class="btn btn-default btn_interested">
                       <i class="fa fa-thumbs-up"></i> Interested
                </button>
                  <span class="viewOffers cursor-pointer" onclick="my_logged_interested(<?php echo $interested->id; ?>)">
                      <?php echo e($interested->intrest_count); ?>

                  </span> 
                 <!--  <span data-toggle="modal" data-target="#logged_interested">
                      10
                  </span> -->

                <span class="post_remaining" id="clock_loged_interest<?php echo e($interested->id); ?>"></span>

                <span class="post_remaining">
                   <?php 
                          $date_a = new DateTime(date("F j, Y, H:i:s"));
                          $date_b = new DateTime($interested->created_at);
                          $interval = date_diff($date_a,$date_b);
                          $stop_date = date('Y-m-d H:i:s', strtotime($interested->created_at . ' +1 day'));
                        
                          $interval_days = $interval->format('%a');
                          if ($interval_days > 0) 
                          {
                            echo '<font style="color:red;">Closed</font>';
                          }
                          else
                          {
                        ?>
                          <script type="text/javascript">
                          $('#clock_loged_interest<?php echo e($interested->id); ?>').countdown("<?php echo e($stop_date); ?>", function(event) 
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
                <span class="offer_id" style="float:left;">Id: #<?php echo e($interested->id); ?></span>
                <div class="post_delete_date">
                    <a href="#">
                      <span >
                        <?php
                          // echo date("H:i ",strtotime($interested->created_at));
                          echo date("d/m/Y", strtotime($interested->created_at) ); ?>
                      </span>
                    </a> &nbsp; 
                    <a onclick = "delete_loggedoffer(<?php echo e($interested->id); ?>)">
                      <span><i class="fa fa-trash" style="cursor: pointer;"></i></span>
                    </a>
                </div>
            </div>
          </div>
          <!--Delete Logged Offer Start-->
          <script>
            function delete_loggedoffer(id) {
            $('#delete_loggedoffer_popup').modal('show');
            $('#logged_interest_id').val(id);
              }
          </script>
              <!--Delete Myoffer Confirmation Popup Start-->
              <div class="modal fade" id="delete_loggedoffer_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete this interested offer?
                    </div>
                    <input type="hidden" name="logged_interest_id" id="logged_interest_id">
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
                var logged_interest_id = $("#logged_interest_id").val();
                  $.ajax({
                  type: 'GET',
                  url: 'delete_logged_interest/'+logged_interest_id,
                  success:function(data)
                  {
                    location.reload();
                  }
                });
              });
            </script>
            <!--Delete Myoffer Confirmation PopupAjax Close-->
            <!--Delete Logged Offer Start-->

          <?php endforeach; ?>
        </div>
        <!-- popup my offer post start -->
            <div class="modal fade" id="logged_interested" role="dialog">
              <div class="modal-dialog">
              <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Logged interested</h4>
                    </div>
                    <div class="modal-body" id="my_logged_interested">
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="refreshdata" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <!-- popup my offer post close -->
            <!-- pagination start -->
              <div class="col-md-12">
                <center id="my_offer_pagination"> <?php echo e($myofferpostinterested->links()); ?> </center>
              </div> 
            <!-- pagination close -->
        
          <div class="col-md-12">
               <div class="footer_data">
                <p style="text-align:center;">
                  <strong>NOTE :</strong> 
                   if there was an ad that you can no longer see, it may be because the ad creator has deleted the offer.
                 </p>
               </div>
             </div>
       </div>
      <?php endif; ?>
    <!-- <div class="wrap" ng-if="$parent.user.gender == 'female' && $parent.user.verify_check != 'VERIFIED'">
      <div>
       <center><h4>Verified users only:</h4> This page has some of the most generous subs within our site, therefore it is only accessible to genuine Femdoms who have been checked and verified by the Casualstar team. Click the button bellow and complete your verifiction today within minutes, its super quick and easy.
        <br>
        <br>
        <div class="add-gallery-photo ng-scope" style="background:transparent;">
          <a href="/verify"><button type="button">Apply for Verification now</button></a></center>
        </div>
      </div>
    </div>
  </div> -->
<button onclick="scrollToTop()" id="scrollToTopButton"><i class="ion-arrow-up-a"></i></button>

<script type="text/javascript">
  window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
      $('#scrollToTopButton').fadeIn();
    } else {
      $('#scrollToTopButton').fadeOut();
    }
  };

  function scrollToTop() {
    $('html,body').animate({scrollTop: 0}, 300);
  }

  document.getElementById('myField').addEventListener('input', event =>
    event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || '').toLocaleString('en-US')
  );
</script>

<script>
$(document).ready(function(){

 $(document).on('click', '#offer_pagination .pagination a', function(event){
  event.preventDefault(); 
  var page = $(this).attr('href').split('page=')[1];
  fetch_data(page);
 });

 function fetch_data(page)
 {
  $.ajax({
   url:"./pagination/fetch_data?page="+page,
   success:function(data)
   {
    $('#table_data').html(data);
    scrollToTop();
   }
  });
 }

//upper pagination
 $(document).on('click', '#my_offer_pagination .pagination a', function(event){
  event.preventDefault(); 
  var page = $(this).attr('href').split('page=')[1];
  fetch_offer_data(page);
 });

 function fetch_offer_data(page)
 {
  $.ajax({
   url:"./upper_pagination/fetch_data?page="+page,
   success:function(data)
   {
     $('#my_offer_interested_users').html(data);
     
   }
  });
 }
//upper pagination close

 
});
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>