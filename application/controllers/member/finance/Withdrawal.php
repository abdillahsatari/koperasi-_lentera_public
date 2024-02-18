<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class Withdrawal extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {

		$content				= '_memberLayouts/withdrawal/index';
		$memeberId				= $this->session->userdata("member_id");
		$query					= 'SELECT WM.*, MBA.bank_account_name, MBA.bank_account_number, MBA.bank_account_owner
									FROM withdrawal_member WM
									JOIN member_bank_account MBA ON WM.wd_member_bank_id = MBA.id
									WHERE WM.wd_member_id = "'.$memeberId.'" ';
		$dataWithdrawal			= $this->CrudModel->q($query);

		$data	= array('session' 			=> SessionType::MEMBER,
						'dataWithdrawal'	=> $dataWithdrawal,
						'csrfName'			=> $this->security->get_csrf_token_name(),
						'csrfToken'			=> $this->security->get_csrf_hash(),
						'content'			=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('wd_ammount', 'Nominal Wd', 'trim|required');
		$this->form_validation->set_rules('member_transaction_code', ' Kode Transaksi', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->index();
		} else {

			$memberId		= $this->session->userdata('member_id');
			$authService 	= authenticateMemberTrCode($memberId, $input["member_transaction_code"]);

			switch ($authService['status']){
				case AuthStatus::AUTHORIZED:
					$wdCode			= "WD-".generatePaymentTransactionCode($memberId);
					$memberCode 	= $this->MemberModel->getMemberReferalCode($memberId);
					$whereWDId		= array("member_id" => $memberId);
					$dataWD			= current($this->CrudModel->gw("member_bank_account", $whereWDId));
					$wdMemberBankId	= $dataWD->id;

					$dataWithdrawal	= array("wd_member_id"			=> $memberId,
											"wd_member_bank_id"		=> $wdMemberBankId,
											"wd_ammount"			=> $input["wd_ammount"],
											"wd_transaction_code"	=> $wdCode,
											"wd_status"				=> MemberWdStatus::WD_NEW,
											"created_at"			=> date('Y-m-d H:i:s'),
											"created_by"			=> $memberId);

					$inputStatus = $this->CrudModel->is("withdrawal_member", $dataWithdrawal);

					if ($inputStatus == "success"){

						$reportDescription  = "[".$memberCode."] Withdraw [".$wdCode."] ke ".$dataWD->bank_account_name." - ".$dataWD->bank_account_number;
						$notifDescription	= "[".$memberCode."] Withdraw [".$wdCode."] ke ".$dataWD->bank_account_name." - ".$dataWD->bank_account_number;

						$data	= array("member_id"				=> $memberId,
										"user_type"				=> CredentialType::MEMBER,
										"receiver"				=> CredentialType::ADMIN,
										"report_description"	=> $reportDescription,
										"notif_description"		=> $notifDescription,
										"reference_link"		=> 'admin/withdrawal/'. strtolower(MemberWdStatus::WD_NEW),
										"created_at"			=> date('Y-m-d H:i:s'));

						$this->ActivityLog->actionLog($data);

						generalAllert("Permintaan Berhasil!", "Permintaan withdrawal anda telah berhasil. Kami akan segera memproses permintaan anda", AllertType::SUCCESS);
					} else {
						generalAllert("Permintaan Gagal!", "Terjadi kesalahan saat mengirim permintaan. Mohon coba beberapa saat lagi", AllertType::FAILED);
					}

					break;
				case AuthStatus::UNAUTHORIZED:
					generalAllert("Permintaan Gagal!", "Kode transaksi salah. Mohon periksa kembali kode transaksi anda", AllertType::FAILED);
					break;
			}

			redirect('member/withdrawal/index');
		}

	}

}
