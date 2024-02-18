<?php
defined('BASEPATH') or exit('No direct script access allowed');

	/**
	 *
	 * Account Helper
	 *
	 * */

	function generateReferralCode($id){

		$random1	= substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2);
		$random2	= substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2);
		$result		= "LDI-".$random1.str_pad($id, 4, "X",STR_PAD_BOTH).$random2;

		return $result;
	}

	function generateToken(){

		$result	= random_string('alnum', 64);

		return $result;
	}

	function generateAutomaticPassword(){

		$result	= random_string('alnum', 4);

		return $result;
	}


	/**
	 *
	 * Finance Helper
	 *
	 * */

	function generatePaymentTransactionCode($id){

		$random1	= substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2);
		$random2	= substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2);
		$result		= $random1.str_pad($id, 6, $random1,STR_PAD_BOTH).$random2;

		return $result;
	}

	function generatePaymentLastCode() {

		return mt_rand(100,999);

	}

	function generatePaymentCode(){

		return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
	}


	function getSimpananInitialsCode($string = null) {
		return array_reduce(
			explode(' ', $string),
			function ($initials, $word) {
				return sprintf('%s%s', $initials, substr($word, 0, 2));
			},
			''
		);
	}

	function generateSimpananTrCode($simpananName = NULL, $memberId = NULL){

		$simpananInitials = strtoupper(getSimpananInitialsCode($simpananName));
		$random1	= substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 2);
		$suffle		= str_pad(date('dmy')."-".$memberId, 8,STR_PAD_BOTH).$random1;

		return $simpananInitials."-".$suffle;

	}


	/**
	 *
	 * Application Helper
	 *
	**/

	function generalAllert($info, $msg, $type){

		$ci = &get_instance();

		switch ($type){
			case AllertType::SUCCESS:
				$ci->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show" role="alert">
															<strong>'.$info.'</strong> <br/> '.$msg.'
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>');
				break;
			case AllertType::FAILED;
				$ci->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
															<strong>'.$info.'</strong> <br/> '.$msg.'
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>');
				break;
			case AllertType::INFO:
				$ci->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible fade show" role="alert">
															<strong>'.$info.'</strong> <br/> '.$msg.'
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>');
				break;
		}
	}

	function generalToaster($info, $msg, $type){
		/* This Features
		 * Undone!!
		 * */
		$ci = &get_instance();

		switch ($type){
			case NotificationType::SUCCESS:
				$ci->session->set_flashdata('notification', '<div class="card">
																<div class="card-body">
																	<div class="row row-cols-auto g-3">
																		<div class="col">
																			<button type="button" class="btn btn-dark px-5 js-notifications" id="clickMe" onclick="lobiboxNotifications('.$info.', '.$msg.', '.NotificationType::SUCCESS.')" hidden>Default</button>
																		</div>
																	</div>
																</div>
															</div>');
				break;
			case NotificationType::FAILED:
				$ci->session->set_flashdata('notification', '<span class="js-notifications" data-info="'.$info.'" data-msg="'.$msg.'" data-type="'.NotificationType::FAILED.'" hidden></span>');
				break;
			case NotificationType::INFO:
				$ci->session->set_flashdata('notification', '<span class="js-notifications" data-info="'.$info.'" data-msg="'.$msg.'" data-type="'.NotificationType::INFO.'" hidden></span>');
				break;
		}

	}

	function getSpace($params) {
		$space = '';
		for ($i = 1; $i <= $params; $i++) {
			$space .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		return $space;
	}
