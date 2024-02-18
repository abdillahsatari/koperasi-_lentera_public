<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class Deposit extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {

		$content				= '_memberLayouts/deposit/index';
		$query					= 'SELECT MD.*, LBA.nama_bank FROM member_deposit MD 
    								JOIN member ME ON MD.deposit_member_id = ME.id 
									JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id = LBA.id
									WHERE MD.deposit_member_id = '.$this->session->userdata("member_id").' ';

		$dataDeposit			= $this->CrudModel->q($query);
		$wherePaymentGatewayIs	= array("status_is_active" => true);
		$getActivePaymentGateway= $this->CrudModel->gw("lentera_bank_account", $wherePaymentGatewayIs);

		$data	= array('session' 			=> SessionType::MEMBER,
						'dataDeposit'		=> $dataDeposit,
						'dataPaymentGateway'=> $getActivePaymentGateway,
						'csrfName'			=> $this->security->get_csrf_token_name(),
						'csrfToken'			=> $this->security->get_csrf_hash(),
						'content'			=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('deposit_lentera_bank_id', 'Gateway', 'trim|required');
		$this->form_validation->set_rules('deposit_ammount', 'Nilai Deposit', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->index();
		} else {

			$memberId		= $this->session->userdata('member_id');
			$depositCode	= "DP-".generatePaymentTransactionCode($memberId);
			$LBAId			= $input["deposit_lentera_bank_id"];

			$dataDeposit	= array("deposit_member_id"			=> $memberId,
									"deposit_lentera_bank_id"	=> $LBAId,
									"deposit_code"				=> generatePaymentCode(),
									"deposit_transaction_code"	=> $depositCode,
									"deposit_last_code"			=> generatePaymentLastCode(),
									"deposit_ammount" 			=> $input["deposit_ammount"],
									"deposit_status"			=> MemberDepositStatus::DEPOSIT_NEW,
									"created_at"				=> date('Y-m-d H:i:s'),
									"created_by"				=> $memberId);

			$insertedStatus = $this->CrudModel->is("member_deposit", $dataDeposit);

			if ($insertedStatus == "success"){

				$whereLBAId			= array("id" => $LBAId);
				$dataLBA			= $this->CrudModel->gw("lentera_bank_account", $whereLBAId);
				$memberCode 		= $this->MemberModel->getMemberReferalCode($memberId);
				$reportDescription  = "[".$memberCode."] Deposit [".$depositCode."] ke ".current($dataLBA)->nama_bank." - ".current($dataLBA)->nomor_rekening;
				$notifDescription	= "[".$memberCode."] Deposit [".$depositCode."] ke ".current($dataLBA)->nama_bank." - ".current($dataLBA)->nomor_rekening;

				$data	= array("member_id"				=> $memberId,
								"user_type"				=> CredentialType::MEMBER,
								"receiver"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"notif_description"		=> $notifDescription,
								"reference_link"		=> 'admin/deposit/'. strtolower(MemberDepositStatus::DEPOSIT_NEW),
								"created_at"			=> date('Y-m-d H:i:s'));
				$this->ActivityLog->actionLog($data);

				generalAllert("Pengajuan Berhasil!", "Pengajuan deposit anda akan segera diproses.", AllertType::SUCCESS);
			} else {
				generalAllert("Pengajuan Gagal!", "Terjadi kesalahan saat melakukan deposit. Mohon coba beberapa saat lagi", AllertType::FAILED);
			}

			redirect('member-deposit');
		}
	}

}
