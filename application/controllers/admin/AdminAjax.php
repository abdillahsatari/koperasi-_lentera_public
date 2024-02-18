<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminAjax extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function generateDataChart(){
//		$input 		= $this->input->post(NULL, TRUE);
		$csrf_token = $this->security->get_csrf_hash();
		$getDate	= date('Y-m-d');
		$getYear	= date("Y", strtotime($getDate));

		$dataChartSimpanan = [];
		$dataChartPinjaman = [];

		for ($i = 1; $i <= 12; $i++){
//			$getMonthName = date("F", mktime(0, 0, 0, $i, 10));
			$querySimpanan 	= 'SELECT *, SUM(simpanan_member.simpanan_member_ammount) total_ammount
                                            FROM simpanan_member
                                            WHERE MONTH(created_at)="' . $i . '"
                                            AND YEAR(created_at)="' . $getYear . '"';

			$queryPinjaman 	= 'SELECT *, SUM(member_pinjaman.pinjaman_member_ammount) total_ammount
                                            FROM member_pinjaman
                                            WHERE MONTH(created_at)="' . $i . '"
                                            AND YEAR(created_at)="' . $getYear . '"
                                            AND approved_status = "'.MemberPinjamanApproval::PINJAMAN_PROCESSED.'"';

			$resSimpanan 	= $this->CrudModel->q($querySimpanan);
			$resPinjaman 	= $this->CrudModel->q($queryPinjaman);

			$collectChartSimpanan= current($resSimpanan)->total_ammount ?: 0;
			$collectChartPinjaman= current($resPinjaman)->total_ammount ?: 0;


			array_push($dataChartSimpanan, intval($collectChartSimpanan));
			array_push($dataChartPinjaman, intval($collectChartPinjaman));

		}

		$result = array('status'				=> 'success',
						'dataChartSimpanan'		=> $dataChartSimpanan,
						'dataChartPinjaman'		=> $dataChartPinjaman,
						'csrf_token'			=> $csrf_token);

		echo json_encode($result);
		die();
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

	public function postadminKtpImg() {
		$file 			= $_FILES['file']['name'];
		$fileName 		= $file;
		$fileExt 		= explode('.', $fileName);
		$fileActualExt 	= strtolower(end($fileExt));
		$fileNameNew 	= 'adminKtp_' . uniqid('', false) . "." . $fileActualExt;

		$config['upload_path']		= "./assets/resources/images/uploads";
		$config['allowed_types']	= 'jpg|jpeg|png';
		$config['file_name']		= $fileNameNew;
		$csrf_token  				= $this->security->get_csrf_hash();

		if (isset($file)) {
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$result = array('status' 		=> 'failed',
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

	public function deletePinjamanContentDetail(){
		$input	= $this->input->post(NULL, TRUE);

		$csrf_token  	= $this->security->get_csrf_hash();
		$where			= array("id" => $input["contentDetailId"]);
		$deletedStatus	= $this->CrudModel->dr("lentera_pinjaman_detail", $where);

		$result = array ('status'		=> $deletedStatus,
						'csrf_token' 	=> $csrf_token);

		echo json_encode($result);
		die();
	}

	public function showMemberPinjamanRequest(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();
		$memberPinjamanId = $input['dataMemberPinjamanId'];

		$query 	= 'SELECT *, MP.id as member_pinjaman_id, MP.created_at as member_pinjaman_created_at, ME.id as member_id, ME.member_referal_code, ME.member_full_name, LPD.pinjaman_content_bunga 
					FROM member_pinjaman MP 
					JOIN member ME ON MP.member_id = ME.id
					JOIN lentera_pinjaman_detail LPD ON MP.pinjaman_content_detail = LPD.id
					AND MP.id = "'.$memberPinjamanId.'"';

		$dataPermohonanPinjaman = $this->CrudModel->q($query);

		if (count($dataPermohonanPinjaman) > 0){
			$result = array('status'	=> 'success',
				'data'		=> current($dataPermohonanPinjaman),
				'csrf_token'=> $csrf_token);
		} else {
			$result = array('status'	=> 'failed',
				'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function showMemberTabunganDetail(){
		$input		= $this->input->post(NULL, TRUE);
		$csrf_token = $this->security->get_csrf_hash();
		$query		= 'SELECT TM.*, ME.member_full_name, ME.member_referal_code, ME.member_email, ME.member_phone_number,
       					LBA.nama_bank, LBA.nomor_rekening, LBA.nama_pemilik_account
						FROM tabungan_member TM 
    					JOIN member ME ON TM.member_id = ME.id
    					JOIN lentera_bank_account LBA ON TM.lentera_bank_id = LBA.id
    					WHERE TM.id = "'.$input["tabunganMemberId"].'"';
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

	public function showMemberWithdrawal(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$query	= 'SELECT WM.*, ME.member_referal_code, ME.member_full_name, ME.member_email, ME.member_phone_number, MBA.*
					FROM withdrawal_member WM
					JOIN member ME ON WM.wd_member_id = ME.id
					JOIN member_bank_account MBA ON WM.wd_member_bank_id = MBA.id
					WHERE WM.id = "'.$input['dataWithdrawalId'].'"';

		$dataWithdrawalShow = $this->CrudModel->q($query);

		if (count($dataWithdrawalShow) > 0){
			$result = array('status'	=> 'success',
							'data'		=> $dataWithdrawalShow,
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();

	}

	public function verifyMemberPhoneNumber(){

		$input	= $this->input->post(NULL, TRUE);

		$csrf_token  	= $this->security->get_csrf_hash();
		$where			= array("member_phone_number" => $input["dataMemberPhoneNumber"]);
		$dataCount		= $this->CrudModel->cw("member", $where);

		$result 		= array ('data'			=> $dataCount,
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

	public function postSetcomDocument(){
		$file 			= $_FILES['file']['name'];
		$fileName 		= $file;
		$fileExt 		= explode('.', $fileName);
		$fileActualExt 	= strtolower(end($fileExt));
		$fileNameNew 	= 'AD-ART' . uniqid('', false) . "." . $fileActualExt;

		$config['upload_path']		= "./assets/resources/documents";
		$config['allowed_types']	= 'pdf';
		$config['file_name']		= $fileNameNew;
		$csrf_token  				= $this->security->get_csrf_hash();

		if (isset($file)) {
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$result = array('status' 		=> 'failed',
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

	public function showAdminPengurusDetail(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$whereId		= array("id" => $input['dataAdminPengurusId']);
		$dataAdminShow	= $this->CrudModel->gw("admin", $whereId);

		if (count($dataAdminShow) > 0){
			$result = array('status'	=> 'success',
							'data'		=> $dataAdminShow,
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
							'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}

	public function postOlshopImg() {
		$file 			= $_FILES['file']['name'];
		$fileName 		= $file;
		$fileExt 		= explode('.', $fileName);
		$fileActualExt 	= strtolower(end($fileExt));
		$fileNameNew 	= 'olshopProductThumbnail_' . uniqid('', false) . "." . $fileActualExt;

		$config['upload_path']		= "./assets/resources/images/uploads";
		$config['allowed_types']	= 'jpg|jpeg|png';
		$config['file_name']		= $fileNameNew;
		$csrf_token  				= $this->security->get_csrf_hash();

		if (isset($file)) {
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$result = array('status' 		=> 'failed',
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

	public function showOlshopCheckoutDetail(){
		$input = $this->input->post(NULL, TRUE);
		$csrf_token  	= $this->security->get_csrf_hash();

		$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductDetail($input["dataLenteraOlshopProductId"]);

		if (count($datacheckoutProduct) > 0){
			$result = array('status'	=> 'success',
							'data'		=> current($datacheckoutProduct),
							'csrf_token'=> $csrf_token);
		} else {

			$result = array('status'	=> 'failed',
				'csrf_token'=> $csrf_token);
		}

		echo json_encode($result);
		die();
	}
}
