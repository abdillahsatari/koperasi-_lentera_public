<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MemberAjax extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function postMemberKtpImg() {
		$file 			= $_FILES['file']['name'];
		$fileName 		= $file;
		$fileExt 		= explode('.', $fileName);
		$fileActualExt 	= strtolower(end($fileExt));
		$fileNameNew 	= 'memberKtp_' . uniqid('', false) . "." . $fileActualExt;

		$config['upload_path']		= "./assets/resources/images/uploads";
		$config['allowed_types']	= 'jpg|jpeg|png';
		$config['file_name']		= $fileNameNew;
		$csrf_token  				= $this->security->get_csrf_hash();

		if (isset($file)) {
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$result = array(
					'status' 		=> 'failed',
					'data' 			=> $this->upload->display_errors(),
					'csrf_token' 	=> $csrf_token
				);
			} else {

				$dataUploaded = $this->upload->data();
				$result = array('status' 		=> 'success',
								'data' 			=> $dataUploaded['file_name'],
								'csrf_token' 	=> $csrf_token
				);
			}
			echo json_encode($result);
		}

		die();
	}

	public function showMemberDeposit(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$query	= 'SELECT MD.*, LBA.*, ME.member_referal_code, ME.member_full_name, ME.member_phone_number, ME.member_email 
					FROM member_deposit MD
    				JOIN member ME ON MD.deposit_member_id = ME.id
					JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id = LBA.id
					WHERE MD.id = '.$input['dataDepositId'].' ';

		$dataDepositShow = $this->CrudModel->q($query);

		if (count($dataDepositShow) > 0){
			$result = array('status'	=> 'success',
							'data'		=> $dataDepositShow,
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function validateBalanceInputAmmount(){

		$input	= $this->input->post(NULL, TRUE);

		$csrf_token  	= $this->security->get_csrf_hash();

		$memberId		= $this->session->userdata("member_id");
		$dataBalance	= current($this->FinanceModel->memberFinance($memberId))->totalWalletBalance;

		$input["dataInputAmmount"] > $dataBalance || $input["dataInputAmmount"] == 0 ? $balanceIsValidated	= false : $balanceIsValidated = true;

		$result 		= array ('data'			=> $balanceIsValidated,
								'csrf_token' 	=> $csrf_token);

		echo json_encode($result);
		die();
	}

	public function showMemberTimDetail(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$whereId		= array("id" => $input['dataMemberId']);
		$dataMemberShow	= $this->CrudModel->gw("member", $whereId);

		if (count($dataMemberShow) > 0){
			$result = array('status'	=> 'success',
							'data'		=> $dataMemberShow,
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showLenteraSimpananContentDetail(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$whereId				= array("id" => $input['dataSimpananId']);
		$dataSimpananFunding	= $this->CrudModel->gw("lentera_simpanan_content", $whereId);

		if (count($dataSimpananFunding) > 0){
			$result = array('status'	=> 'success',
							'data'		=> $dataSimpananFunding,
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showLenteraPinjamanContentDetail(){

		$input		 	= $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$pinjamanSelectedContent = $this->PinjamanModel->getPinjamanContentDetail($input["dataPinjamanContentId"]);

		if (count($pinjamanSelectedContent) > 0){
			$result = array('status'	=> 'success',
							'data'		=> current($pinjamanSelectedContent),
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showMemberPinjamanRequest(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();
		$memberPinjamanDetailId = $input['dataMemberPinjamanDetailId'];

		$whereId = array("id" => $memberPinjamanDetailId);
		$dataPinjamanDetail	= $this->CrudModel->gw("member_pinjaman_detail", $whereId);

		if (count($dataPinjamanDetail) > 0){
			$result = array('status'	=> 'success',
				'data'		=> current($dataPinjamanDetail),
				'csrf_token'=> $csrf_token);
		} else {
			$result = array('status'	=> 'failed',
				'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showMemberTabunganDetail(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$query = 'SELECT TM.*, ME.member_full_name, ME.member_referal_code, LBA.nama_bank, LBA.nomor_rekening, LBA.nama_pemilik_account
						FROM tabungan_member TM 
						JOIN member ME ON TM.member_id = ME.id
						JOIN lentera_bank_account LBA ON TM.lentera_bank_id = LBA.id 
						WHERE TM.id = "'.$input["dataTabunganId"].'"';

		$dataTabunganShow = $this->CrudModel->q($query);

		if (count($dataTabunganShow) > 0){
			$result = array('status'	=> 'success',
							'data'		=> current($dataTabunganShow),
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showMemberTabunganTfDetail(){
		$input		= $this->input->post(NULL, TRUE);
		$csrf_token = $this->security->get_csrf_hash();

		if ($input["tabunganTfMemberBankId"]){
			$query		= 'SELECT TMT.*, ME.member_full_name, ME.member_referal_code, ME.member_email, ME.member_phone_number,
       					MBA.bank_account_name, MBA.bank_account_number, MBA.bank_account_owner
						FROM tabungan_member_transfer TMT 
    					JOIN member ME ON TMT.member_id = ME.id
    					JOIN member_bank_account MBA ON TMT.member_bank_id = MBA.id
    					WHERE TMT.id = "'.$input["tabunganTfMemberId"].'"';
		} else {
			$query		= 'SELECT TMT.*, ME.member_full_name, ME.member_referal_code, ME.member_email, ME.member_phone_number
						FROM tabungan_member_transfer TMT 
    					JOIN member ME ON TMT.member_id = ME.id
    					WHERE TMT.id = "'.$input["tabunganTfMemberId"].'"';
		}

		$dataTabunganShow	= $this->CrudModel->q($query);

		if (count($dataTabunganShow) > 0){
			$result = array('status'	=> 'success',
				'data'		=> current($dataTabunganShow),
				'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
				'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showLenteraOlshopProductDetail(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$whereId			= array("id" => $input['dataLenteraOlshopProductId']);
		$dataProductShow	= $this->CrudModel->gw("lentera_olshop_product", $whereId);

		if (count($dataProductShow) > 0){
			$result = array('status'	=> 'success',
							'data'		=> current($dataProductShow),
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}
}
