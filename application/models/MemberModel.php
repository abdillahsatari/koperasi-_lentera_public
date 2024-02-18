<?php

class MemberModel extends CrudModel {


	/**
	 *
	 *	Getter
	 *
	 **/

	public function getMemberReferalCode($id){
		$query 	= 'SELECT member_referal_code FROM member WHERE id = "'.$id.'"';
		$data	= $this->q($query);
		$referal= current($data)->member_referal_code;

		return $referal;
	}

	public function getMemberActiveStatus($id){
		$whereId	= array('id'	=> $id);
		$getMember 	= $this->gw('member', $whereId);
		$result 	= array("member_is_kyc" 		=> current($getMember)->member_is_kyc,
							"member_is_simpo"		=> current($getMember)->member_is_simpo,
							"member_is_simwa"		=> current($getMember)->member_is_simwa,
							"member_is_activated"	=> current($getMember)->member_is_activated);

		return $result;
	}


	/**
	 *
	 *	Setter
	 *
	 **/


}
