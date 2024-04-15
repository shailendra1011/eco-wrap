<?php 

return [
	// Vendor messages for response
	'some_went_wrong'			=>	'Something went wrong. Please try again',
	'unregistered_email'		=>	'Email ID you have entered is not registered with us, Please check and renter.',
	'vendor_doc_pending'		=>	'You need to updated required documents.',
	'signup_success'			=>	'Sign up Successful',
	'login_success'				=>	'Login Successful',
	'logout_success'			=>	'Logout Successful',
	'invalid_credentials'		=>	'Invalid Credentials',
	'invalid_reset_token'		=>	'Invalid reset password token. Please generate new reset password token.',
	'profile_disabled'			=>	'Your profile has been blocked. Please contact to admin.',
	'profile_deleted'			=>	'Your profile has been deleted by admin.',
	'password_reset_link'		=> 	'Password reset link has been sent to entered email id',
	'password_reset_success'	=>	'Password has been reset successfully.',
	'password_reset_fail'		=>	'Some problem occured while resetting password. Please try again.',
	'already_signedup'			=>	'Phone number already registered, Please use email and password to login.',
	'otp_sent'					=>	'OTP send successfully',
	'otp_validated'				=>	'OTP verified successfully',
	'otp_invalid'				=>	'Invalid OTP',
	'please_signup'				=>	'Entered email is not registered. Please sign up.',
	'product_list'				=>	'Product List',
	'product_details'			=>	'Product Details',
	'old_password_mismatch'		=>	'Incorrect existing password',
	'password_exists'			=>	'New password can not be same as old password',
	'password_updated'			=>	'Password updated successfully',
	'profile_updated'			=>	'Profile updated successfully',
	'profile_details'			=>	'Profile Details',
	'order_list'				=>	'Order List.',
	'order_not_found'			=>	'Order Not Found!',
	'order_details'				=>	'Order Details',
	'review_list'				=>	'Reviews List',
	'no_review'					=>	'No reviews yet!',
	'invalid_review_id'			=>	'Review Id does not exists.',
	'review_data'				=>	'Review data',
	'review_updated'			=>	'Review updated successfully',
	'complaint_posted'			=>	'Complaint registered successfully',
	'complaint_list'			=>	'Complaint List',
	'no_complaints'				=>	'No Complaint(s) Found!',
	'complaint_data'			=>	'Complaint Data',
	'industry_list'				=>	'Industry list',
	'featured_product_list'		=>	'Featured Product List.',

	'vendor'	=>	[
		'basic_details_updated'	=>	'Profile details updated successfully',
		'documents_updated'		=>	'Documents updated successfully',
		'bank_details_updated'	=> 	'Bank details updated successfully',
		'approved'				=> 	'Profile approved',
		'approval_waiting'		=>	'Profile not approved yet by admin',
		'approval_rejected'		=>	'Profile has been rejected by admin',
		'signup_incomplete'		=>	'Continue sing up process',
		'product_added'			=> 	'Product has been added successfully',
		'product_updated'		=> 	'Product has been updated successfully',
		'product_deleted'		=> 	'Product has been deleted successfully',
		'details'				=>	'Vendor Details',
		'order_accepted'		=>	'Order has been accepted successfully',
		'order_rejected'		=>	'Order has been rejected successfully',
		'new_order_list'		=>	':count new orders found',
		'new_order_not_found'	=>	'Orders Not Found!',
		'transaction_list'		=>	'Transaction List',
		'no_transaction'		=>	'No transactions found!',
		'featured_ad_success'	=>	'Featured Ad request submitted successfully.',
		'featured_ad_list'		=>	'Featured Ad list',
		'featured_ad_details'	=>	'Featured Ad Deatils',
		'ad_not_found'			=>	'No Featured Ad(s) Found!',
		'status_online'			=>	'You are online now.',
		'status_offline'		=>	'You are offline now',
		'online_status'			=>	'Working Status',
		'feature_prod_succ'		=>	'Product(s) have been made featured successfully.',
		'feature_prod_failed'	=>	'Be patient your money with us is safe if deducted from account. Please raise a refund request from failed transactions under transactions tab.',
		'banner_success'		=>	'Banner has been created successfully.',
		'banner_unsuccess'		=>	'Banner can not be created due to server error. If any amount deducted from your account it will be reflected in transactions under failed transactions.'
	],

	'user'	=>	[
        'success'                     	=> 	'success',
		'signup_success'				=> 	'success',
		'user_deleted'					=>	'Account deleted successfully.',
		'user_delete_failed'			=>	'Account delete failed! Please try again.',
		'login_success' 				=> 'user login successfully',
		'password_missmatch'			=> 'Password mismatch',
        'invalid_credential'      		=> 'Invalid credential',
        'user_not_registered'     		=> 'User Not Registered',
        'mobile is already registered' 	=> 'mobile is already registered',	
		'email is already registered' 	=> 'email is already registered',
		'email and mobile is not registered' => 'email and mobile is not registered',
		'cart'	=>	[
			'coupon_already_used'		=>	'Coupon can only be used once.',
			'min_cart_value_failed'		=>	'Minimum cart value should be :price to apply this coupon code.',
			'coupon_code_applied'		=>	'Coupon code applied successfully.'
		]
	],


	'driver'	=>	[
		'success'					=> 'success',
		'signup_success'			=>'Registration successfully done',
		'doument_add_successfuly'	=>'success',
		'not_applied'				=> 'you have not applied document for verification',
		'verification_under_process'  => 'you document verification is under process',
		'admin_reject_doc'			=> 'Admin has rejected your document, apply again with correct document',
		'login_success' 			=> 'driver login successfully',
		'password_missmatch'		=> 'Password mismatch',
		'invalid_credential'		=> 'Invalid credential',
		'driver_not_registered'		=> 'Driver Not Registered',
		'no_incoming_order'			=> 'No incoming order',
		'mobile is already registered' => 'mobile is already registered',	
		  'email is already registered' => 'email is already registered',
		  'email and mobile is not registered' => 'email and mobile is not registered'
	],

	'master'	=>	[
		'variant_list'			=>	'Product Variant List',
		'variant_values'		=>	'Product Variant Values List',
		'state_list'			=>	'State List',
		'city_list'				=>	'City List',
		'question_list'			=>	'Question List.',
		'answer'				=>	'Answer',
		'terms_conditions'		=>	'Terms & Conditions'
	],

	'promocode'	=>	[
		'list'					=>	'Promocode List.',
		'not_found'				=>	'No promocode(s) available.',
		'already_used'			=>	'Promocode already used.',
		'invalid_code'			=>	'Entered promocode is inavalid.',
		'code_expired'			=>	'Entered promocode has expired.',
		'code_success'			=>	'Promocode successfully applied.',
		'min_cart_value'		=>	'Minimum cart value should be :value to apply this promocode.',
		'code_removed'			=>	'Promocode removed successfully.'
	],

	'web'	=>	[
		'vendor'	=>	[
			'product_not_found'	=>	'Invalid Product ID. Please try again.',
		]
	],

	'admin'	=>	[
		'promocode_added'		=>	'Promocode saved successfully.',
		'settings_updated'		=>	'Site settings have been updated successfully.'
	],
];
