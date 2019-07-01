<?php
//
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
	
	Route::get('/', ['uses' => 'HomeController@index', 'as' => 'index']);
	Route::get('contact', ['uses' => 'HomeController@contact', 'as' => 'contact']);
	Route::get('about', ['uses' => 'HomeController@about', 'as' => 'about']);
	Route::get('faq', ['uses' => 'HomeController@faq', 'as' => 'faq']);

	Route::get('terms-and-conditions', ['uses' => 'HomeController@terms', 'as' => 'terms']);
	Route::get('privacy', ['uses' => 'HomeController@privacy', 'as' => 'privacy']);
	Route::get('safety', ['uses' => 'HomeController@safety', 'as' => 'safety']);
	Route::post('send-contact', ['uses' => 'HomeController@sendContact', 'as' => 'send.contact']);
    // Route for the Competitions Public page;
	Route::get('competitions',['uses' => 'HomeController@competitions','as'=>'competitions']);
	// Route for the Competitions Public page;
	Route::get('login', ['uses' => 'Auth\AuthController@login', 'as' => 'login']);
	Route::post('login', ['uses' => 'Auth\AuthController@doLogin', 'as' => 'do.login']);
	Route::post('register', ['uses' => 'Auth\AuthController@register', 'as' => 'register']);
	Route::post('register_twitter', ['uses' => 'Auth\AuthController@register_twitter', 'as' => 'register_twitter']);
	Route::post('check-username', ['uses' => 'SocialAuthController@checkUsername', 'as' => 'check.username']);
	Route::post('check-email', ['uses' => 'SocialAuthController@checkEmail', 'as' => 'check.email']);
	Route::get('/login/{provider?}',[
	    'uses' => 'SocialAuthController@getSocialAuth',
	    'as'   => 'auth.getSocialAuth'
	]);

	Route::get('/login/callback/{provider?}',[
	    'uses' => 'SocialAuthController@getSocialAuthCallback',
	    'as'   => 'auth.getSocialAuthCallback'
	]);

	Route::group(['middleware' => ['auth', 'redirectIfUsernameSet']], function() {
		Route::get('set-username', ['uses' => 'SocialAuthController@setUsername', 'as' => 'set.username']);
		Route::post('username-set', ['uses' => 'SocialAuthController@usernameSet', 'as' => 'username.set']);
	});

    Route::group(['middleware' => ['auth', 'usernameSet','female']], function() {
        Route::get('services', ['uses' => 'Api\serviceController@services', 'as' => 'services']);
    });
	Route::group(['middleware' => ['auth', 'usernameSet']], function() {
		Route::get('banner-ads', ['uses' => 'HomeController@bannerAds', 'as' => 'banner.ads']);
		Route::get('explore', ['uses' => 'HomeController@explore', 'as' => 'explore']);
		Route::get('offers', ['uses' => 'HomeController@offers', 'as' => 'offers']);

		//pgination start
		Route::get('pagination/fetch_data','offerPostController@ajxfetchData');
		Route::get('upper_pagination/fetch_data','offerPostController@upper_pagination');
		//pagination close

		Route::get('supersubs', ['uses' => 'HomeController@supersubs', 'as' => 'supersubs']);
		Route::get('users/{username}', ['uses' => 'UserController@show', 'as' => 'show.user']);
		Route::get('messages/{username?}', ['uses' => 'ProfileController@messages', 'as' => 'messages']);
		Route::get('dashboard', ['uses' => 'ProfileController@dashboard', 'as' => 'dashboard']);
		Route::get('profile', ['uses' => 'ProfileController@profile', 'as' => 'profile']);
		Route::get('verify', ['uses' => 'ProfileController@verify', 'as' => 'verify']); // APInfo
		Route::get('activity', ['uses' => 'ProfileController@activity', 'as' => 'activity']);
		Route::get('settings', ['uses' => 'ProfileController@settings', 'as' => 'settings']);
		Route::get('logout', ['uses' => 'Auth\AuthController@logout', 'as' => 'logout']);
		// Route::get('loginTwit', ['uses'=>'SocialAuthController@loginTwit', 'as'=> 'loginTwit']);

		Route::get('/loginTwit/{provider?}',[
			'uses' => 'SocialAuthController@loginTwit',
			'as'   => 'auth.loginTwit'
		]);

		Route::get('/loginTwit/callback/{provider?}',[
			'uses' => 'SocialAuthController@loginTwitAuthCallback',
			'as'   => 'auth.loginTwitAuthCallback'
		]);
		
		Route::get('close-account', ['uses' => 'UserController@closeAccount', 'as' => 'close.account']);
		// Route::get('twitterUserTimeLine', 'TwitterController@twitterUserTimeLine');
		Route::get('twitterUserTimeLine', ['uses' => 'TwitterController@twitterUserTimeLine', 'as' => 'twit.account']);
		Route::post('tweet', ['uses'=>'TwitterController@tweet', 'as'=>'post.tweet']);
		Route::get('donations', ['uses' => 'PaymentController@donations', 'as' => 'donations']);
		Route::get('account-details', ['uses' => 'PaymentController@accountDetails', 'as' => 'account.details']);
		Route::post('payment', ['uses' => 'PaymentController@index', 'as' => 'payment']);
		Route::get('payment-completion/{id}/{status}', ['uses' => 'PaymentController@completion', 'as' => 'payment.completion']);


		Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
            Route::get('terms-and-condition',['uses' => 'AdminController@termsCondition', 'as' => 'admin.termsCondition']);
			Route::get('/', ['uses' => 'AdminController@index', 'as' => 'admin']);
			Route::get('/admin2', ['uses' => 'AdminController@index2', 'as' => 'admin2']); // APInfo
      Route::get('/compition-users/{id}', ['uses' => 'AdminController@compition_users', 'as' => 'compition_users']);
			Route::get('/compitions', ['uses' => 'AdminController@compitions', 'as' => 'compitions']);
			Route::get('/banner-ads', ['uses' => 'AdminController@bannerAds', 'as' => 'admin.banner.ads']); // APInfo
			Route::group(['prefix' => 'api'], function() {
                //Route::post('add-terms-condition',['uses' => 'AdminController@addTermsCondition', 'as' => 'admin.addTermsCondition']);
                Route::post('edit-terms-condition',['uses' => 'AdminController@editTermsCondition', 'as' => 'admin.editTermsCondition']);
				Route::post('add-terms-condition',['uses' => 'AdminController@addTermsCondition', 'as' => 'admin.addTermsCondition']);
				Route::get('list-terms-condition',['uses' => 'AdminController@listTermsCondition', 'as' => 'admin.listTermsCondition']);
				//Route::get('list-terms-condition',['uses' => 'AdminController@listTermsCondition', 'as' => 'admin.listTermsCondition']);
			    Route::get('admin_users', ['uses' => 'AdminController@admin_users', 'as' => 'admin_users']);
				Route::get('users', ['uses' => 'AdminController@users', 'as' => 'users']);
				Route::get('getCompitions', ['uses' => 'AdminController@getCompitions', 'as' => 'getCompitions']);
				Route::get('getcompitionuser/{id}', ['uses' => 'AdminController@getcompitionuser', 'as' => 'getcompitionuser']);
				Route::any('deleteCompition/{id}',['uses'=>'AdminController@deleteCompition','as'=>'delete.Compition']);
				Route::get('total-users', ['uses' => 'AdminController@totalUsers', 'as' => 'total.users']);
				Route::get('subscriptions', ['uses' => 'AdminController@subscriptions', 'as' => 'subscriptions']);
				Route::get('delete-subscription/{id}', ['uses' => 'AdminController@deleteSubscription', 'as' => 'delete.subscription']);
				Route::get('delete-photo/{id}', ['uses' => 'AdminController@deletePhoto', 'as' => 'delete.photo']);
				Route::post('lock-user', ['uses' => 'AdminController@lockUser']);
	            Route::post('unlock-user', ['uses' => 'AdminController@unlockUser']);
	            Route::post('email-user', ['uses' => 'AdminController@emailUser']);
	            Route::post('approve-user', ['uses' => 'AdminController@approveUserData']);
	            Route::post('cancel-user', ['uses' => 'AdminController@cancelUserData']);
				Route::post('delete-selfie-photo', ['uses' => 'AdminController@deleteSelfiePhoto']); // APInfo
	            Route::post('save-notes', ['uses' => 'AdminController@notesUserData']);
	            Route::resource('interest', 'Api\InterestController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
                Route::get('banner-ads', ['uses' => 'AdminController@bannerAdsList']); // APInfo
                Route::post('approve-banner-ad-request', ['uses' => 'AdminController@approveBannerAdRequest']); // APInfo
                Route::post('approve-video-banner-ad-request', ['uses' => 'AdminController@approveVideoBannerAdRequest']); // APInfo
                Route::post('deny-banner-ad-request', ['uses' => 'AdminController@denyBannerAdRequest']); // APInfo
                Route::post('remove-banner-ad', ['uses' => 'AdminController@removeBannerAd']); // APInfo
                Route::get('banner-ad-requests', ['uses' => 'AdminController@bannerAdRequestsList']); // APInfo
                Route::get('active-banner-ad-requests', ['uses' => 'AdminController@activeBannerAdRequestsList']); // APInfo
                Route::resource('banner-ad-request', 'Api\BannerAdRequestController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			         /***
				         * custom routes for competition
				       */
				     Route::post('competition-save',['uses'=>'AdminController@competitionSave']);
				    Route::post('competition-edit',['uses'=>'AdminController@competitionEdit']);
      });
		});

		Route::group(['prefix' => 'api'], function() {
            Route::get('active-banner-ad', ['uses' => 'Api\BannerAdRequestController@activeBannerAd']); // APInfo

			Route::resource('user', 'Api\UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::get('browse', ['uses' => 'Api\UserController@browse', 'as' => 'views']);
            Route::get('banner-ad', ['uses' => 'Api\BannerAdController@index', 'as' => 'get.bannerAds']);
            Route::post('banner-ad-request', ['uses' => 'Api\BannerAdRequestController@store', 'as' => 'create.bannerAd.request']);
            Route::get('supersubs', ['uses' => 'Api\UserController@supersubs', 'as' => 'get.supersubs']);
			Route::get('favorites', ['uses' => 'Api\UserController@favorites', 'as' => 'favorites']);
			Route::post('delete-chat', ['uses' => 'Api\UserController@deleteChat', 'as' => 'delete.chat']);
			Route::post('delete-message', ['uses' => 'Api\UserController@deleteMessage', 'as' => 'delete.message']);
			Route::post('add-profile-photo', ['uses' => 'Api\UserController@addProfilePhoto', 'as' => 'add.profile.photo']);
			Route::post('delete-profile-photo', ['uses' => 'Api\UserController@deleteProfilePhoto', 'as' => 'delete.profile.photo']);
			Route::post('add-cover-photo', ['uses' => 'Api\UserController@addCoverPhoto', 'as' => 'add.cover.photo']);
			Route::post('add-selfie-photo', ['uses' => 'Api\UserController@addSelfiePhoto', 'as' => 'add.profile.photo']);
			Route::get('views', ['uses' => 'Api\UserController@getViews', 'as' => 'views']);
			Route::get('winks', ['uses' => 'Api\UserController@getWinks', 'as' => 'winks']);
			Route::post('toggle-favorite/{id}', ['uses' => 'Api\FavoriteController@toggleFavorite', 'as' => 'toggle.favorite']);
			Route::post('wink/{id}', ['uses' => 'Api\UserController@wink', 'as' => 'wink']);
			Route::get('get-user/{id}', ['uses' => 'Api\UserController@getById', 'as' => 'get.user']);

			Route::get('interest', ['uses' => 'Api\UserInterestController@interest', 'as' => 'interest']);
			Route::post('update-location', ['uses' => 'Api\UserController@updateLocation', 'as' => 'update.location']);
			Route::post('update-email', ['uses' => 'Api\UserController@updateEmail', 'as' => 'update.email']);
			Route::post('change-password', ['uses' => 'Api\UserController@changePassword', 'as' => 'change.password']);
			Route::get('donate/{username}', ['uses' => 'Api\UserController@donate', 'as' => 'donate']);

			Route::resource('user-interest', 'Api\UserInterestController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::resource('photo', 'Api\PhotoController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::resource('photo-like', 'Api\PhotoLikeController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::resource('photo-comment', 'Api\PhotoCommentController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::resource('profile-comment', 'Api\UserController@profile_comment');
			Route::resource('privatePhoto', 'Api\PrivatePhotoController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::resource('favorite', 'Api\FavoriteController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
			Route::patch('photo-reorder', ['uses' => 'Api\PhotoController@reorder', 'as' => 'photo.reorder']);
             Route::patch('private-photo-reorder', ['uses' => 'Api\PrivatePhotoController@reorder', 'as' => 'private.photo.reorder']);
            
			Route::get('donation-attempts', ['uses' => 'Api\UserController@getDonationAttempts', 'as' => 'donation.attempts']);
			Route::get('getAllCurrency', 'Api\serviceController@getAllCurrency');
			Route::get('getAllService', 'Api\serviceController@getAllService');
			Route::get('getAllServiceVariable', 'Api\serviceController@getAllServiceVariable');
			Route::get('updateUserCurrency/{currency_code}', 'Api\serviceController@updateUserCurrency');
			Route::post('saveUserService', 'Api\serviceController@saveService');
			Route::get('deleteUserService/{serviceId}', 'Api\serviceController@deleteUserService');
			Route::get('changeServiceRequestStatus/{id}/{status}', 'Api\serviceController@changeServiceRequestStatus');
			Route::get('getAllUserServices/{username}', 'Api\serviceController@getAllUserServices');
			Route::post('sendServiceRequest', 'Api\serviceController@sendServiceRequest');
			Route::get('getPendingServiceRequest', 'Api\serviceController@getPendingServiceRequest');
			Route::get('getAllServiceRequest/{currentPage}', 'Api\serviceController@getAllServiceRequest');
			Route::get('privateGalleryAccess/{username}', 'Api\serviceController@privateGalleryAccess');
			Route::get('deleteNotification/{id}', 'Api\serviceController@deleteNotification');
			Route::get('sendNotification','Api\serviceController@sendNotification');
			Route::get('getNotifications','Api\serviceController@getNotifications');
			Route::get('getNotificationCount','Api\serviceController@getNotificationCount');
			Route::get('checkAccessPeriod','Api\serviceController@checkAccessPeriod');
			Route::post('saveNewService', 'Api\serviceController@saveNewService');
			Route::post('updateNewService', 'Api\serviceController@updateNewService');
			Route::post('delNewService', 'Api\serviceController@delNewService');
			Route::post('enable-twit', 'Api\serviceController@enableTwit');
			Route::post('save-twit-limit', 'Api\serviceController@saveTwitLimit');
			
		});
	});

	// Password Reset Routes...
	$this->get('password/reset/{token?}', ['uses' => 'Auth\PasswordController@showResetForm', 'as' => 'reset.password']);
	$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
	$this->post('password/reset', 'Auth\PasswordController@reset');
	$this->get('test/mail', 'Api\serviceController@testmail');


	//offer routes
	Route::post('offerpost','offerPostController@offer');
	Route::get('interested/{id}/{user_id}/','offerPostController@interested');
	Route::get('delete/{id}','offerPostController@deletePost');
	Route::get('deletemyoffer/{id}','offerPostController@deletemyoffer');
	Route::get('delete_logged_interest/{id}','offerPostController@delete_logged_interest');
	Route::get('offerpost/{id}','offerPostController@post_intrest_users');
	Route::get('myofferpost/{id}','offerPostController@myoffer_intrest_users');
	Route::post('send_offer_message','offerPostController@send_offer_message');
	Route::get('logged_interested/{id}','offerPostController@logged_interested');

	//pgp activation
	Route::get('activate','offerPostController@pgp_activation');
	
	// Route::get('offersrr','offerPostController@getData');

	//Competitions Routes
	Route::post('competition-user','competitionController@add');
	Route::get('competitiondelete/{id}','competitionController@competitiondelete');
	Route::post('editdate','competitionController@editd');
	
	Route::get('browseuser', ['uses' => 'SearchCompetitionUserController@browse', 'as' => 'views']);
	Route::get('live_search/action', 'SearchCompetitionUserController@action')->name('live_search.action');
    Route::post('terms_store','competitionController@termsstore');
    // 	Route::post('terms_store','competitionController@termsstoreedit');
    // 	Route::post('confirm_vote','competitionController@confirm_vote');
	Route::get('confirm_vote/{confirm_vote}/{competitionid}/{competition_userid}/{competition_username}','competitionController@confirm_vote');
	Route::get('expand_image/{id}','competitionController@expand_image');
    // first place amount edit
	Route::get('competition_vote_amount_edit/{firstplace_amount}/{hidden_user_id}','HomeController@competition_vote_amount_edit');
	
	//second place amount edit
	Route::get('competition_vote_second_place_amount_edit/{secondplace_amount}/{hidden_user_id}','HomeController@competition_vote_second_place_amount_edit');
	
	//third place amount edit
	Route::get('competition_vote_third_place_amount_edit/{thirdplace_amount}/{hidden_user_id}','HomeController@competition_vote_third_place_amount_edit');
	Route::resource('competition_user', 'CompetitionUserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
	Route::post('edit_title','competitionController@edit_title');
	Route::get('comment_user_data/{id}','competitionController@comment_user_data');
	Route::post('confirm_comment','competitionController@confirm_comment');
	Route::get('delete_comment/{id}','competitionController@delete_comment');
	Route::get('allcompetition_delete','competitionController@delete_all_competitions');
	
