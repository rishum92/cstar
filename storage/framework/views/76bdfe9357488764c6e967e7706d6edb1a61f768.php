<div class="modal fade" id="altDonateModal" tabindex="-1" role="dialog" aria-labelledby="altDonateModal" data-ng-controller="ChatController">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form name="altDonate" ng-submit="submitModal('altDonate')" files="true" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ion-android-close"></i></button>
          <h2>PAY ME (by Another Method)</h2>
        </div>
        <div class="hidden" id="altDonateUsername"></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Message</label>
            <textarea id="altDonateMessage" style="height:130px;" type="text" name="message" ng-model="altDonate['data'].message"  class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" ng-disabled="altDonate.$invalid" class="form-btn main-btn stroke-btn"><i class="fa fa-check"></i></input>
        </div>
      </form>
    </div>
  </div>
</div>