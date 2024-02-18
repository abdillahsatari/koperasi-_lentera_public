<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class Tabungan extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {
		$memberId		= $this->session->userdata("member_id");
		$dataTabungan	= $this->TabunganModel->getAllTabunganMemberList(NULL, $memberId);
		$content		= '_memberLayouts/tabungan/index';
		$data			= array('session' 				=> SessionType::MEMBER,
								'csrfName'				=> $this->security->get_csrf_token_name(),
								'csrfToken'				=> $this->security->get_csrf_hash(),
								'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance,
								'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance($memberId))->countTabunganImbalJasa,
								'dataTabungan'			=> $dataTabungan,
								'content'				=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function create(){
		$dataGateway	= $this->CrudModel->ga("lentera_bank_account");
		$content		= '_memberLayouts/tabungan/create';
		$memberId		= $this->session->userdata('member_id');
		$memberStatus 	= $this->MemberModel->getMemberActiveStatus($memberId);
		$data			= array('session' 			=> SessionType::MEMBER,
								'memberIsKyc'		=> $memberStatus["member_is_kyc"],
								'memberIsSimwa'		=> $memberStatus["member_is_simwa"],
								'memberIsSimpo'		=> $memberStatus["member_is_simpo"],
								'memberIsActivated'	=> $memberStatus["member_is_activated"],
								'csrfName'			=> $this->security->get_csrf_token_name(),
								'csrfToken'			=> $this->security->get_csrf_hash(),
								'dataGateway'		=> $dataGateway,
								'content'			=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input 		= $this->input->post(NULL, TRUE);
		$memberId	= $this->session->userdata("member_id");
		$this->form_validation->set_rules('tabungan_ammount', 'Nominal Tabungan', 'trim|required|callback_ammountValidation');
		$this->form_validation->set_rules('lentera_bank_id', 'Rekening Tabungan', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->create();
		} else {
			$tabunganCode	= "TB-".generatePaymentTransactionCode($memberId);
			$data			= array("member_id" 		=> $memberId,
									"lentera_bank_id"	=> $input["lentera_bank_id"],
									"setting_id"		=> 1,
									"tabungan_ammount"	=> $input["tabungan_ammount"],
									"tabungan_tr_code"	=> $tabunganCode,
									"tabungan_code"		=> generatePaymentCode(),
									"tabungan_approval"	=> MemberTabunganApproval::TABUNGAN_NEW,
									"created_at"		=> date('Y-m-d H:i:s'),
									"created_by"		=> $memberId);

			$updateStatus = $this->CrudModel->is("tabungan_member", $data);

			if ($updateStatus == "success"){
				$whereLBAId			= array("id" => $input["lentera_bank_id"]);
				$dataLBA			= $this->CrudModel->gw("lentera_bank_account", $whereLBAId);
				$memberCode 		= $this->MemberModel->getMemberReferalCode($memberId);
				$reportDescription  = "[".$memberCode."] Menabung [".$tabunganCode."] ke ".current($dataLBA)->nama_bank." - ".current($dataLBA)->nomor_rekening;
				$notifDescription	= "[".$memberCode."] Menabung [".$tabunganCode."] ke ".current($dataLBA)->nama_bank." - ".current($dataLBA)->nomor_rekening;

				$data	= array("member_id"				=> $memberId,
								"user_type"				=> CredentialType::MEMBER,
								"receiver"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"notif_description"		=> $notifDescription,
								"reference_link"		=> 'admin/tabungan/anggota?type='. strtolower(MemberTabunganApproval::TABUNGAN_NEW),
								"created_at"			=> date('Y-m-d H:i:s'));
				$this->ActivityLog->actionLog($data);
				generalAllert("Pengajuan Berhasil!", "Pengajuan Pembukaan Tabungan Anda Akan Segera Diproses.", AllertType::SUCCESS);
			} else {
				generalAllert("Pengajuan Gagal !", "Terjadi kesalahan saat melakukan pengajuan pembukaan tabungan. Silahkan coba lagi", AllertType::FAILED);
			}

			$this->index();
		}
	}

	public function ammountValidation($params = null) {
		$getAvailabelReferralCode = $this->CrudModel->gw('tabungan_Setting', array('id' => 1));
		if ( $params < current($getAvailabelReferralCode)->required_ammount) {
			$this->form_validation->set_message('ammountValidation', '{field} Minimal Rp.'. current($getAvailabelReferralCode)->required_ammount );
			return FALSE;
		} else {
			return TRUE;
		}
	}

}
