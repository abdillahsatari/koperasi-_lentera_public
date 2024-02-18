<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class TabunganTf extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {
		$memberId		= $this->session->userdata("member_id");
		$dataTabunganTf	= $this->TabunganModel->getAllTabunganTfMemberList(NULL, $memberId);
		$content		= '_memberLayouts/tabunganTf/index';
		$data			= array('session' 				=> SessionType::MEMBER,
								'csrfName'				=> $this->security->get_csrf_token_name(),
								'csrfToken'				=> $this->security->get_csrf_hash(),
								'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance,
								'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance($memberId))->countTabunganImbalJasa,
								'dataTabunganTf'		=> $dataTabunganTf,
								'content'				=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function create(){
		$content		= '_memberLayouts/tabunganTf/create';
		$memberId		= $this->session->userdata('member_id');
		$memberStatus 	= $this->MemberModel->getMemberActiveStatus($memberId);
		$data			= array('session' 			=> SessionType::MEMBER,
								'memberIsKyc'		=> $memberStatus["member_is_kyc"],
								'memberIsSimwa'		=> $memberStatus["member_is_simwa"],
								'memberIsSimpo'		=> $memberStatus["member_is_simpo"],
								'memberIsActivated'	=> $memberStatus["member_is_activated"],
								'csrfName'			=> $this->security->get_csrf_token_name(),
								'csrfToken'			=> $this->security->get_csrf_hash(),
								'content'			=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input 		= $this->input->post(NULL, TRUE);
		$memberId	= $this->session->userdata("member_id");
		$this->form_validation->set_rules('tabungan_tf_ammount', 'Nominal Transfer', 'trim|required|callback_ammountValidation');
		$this->form_validation->set_rules('tabungan_tf_type', 'Jenis Transfer', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->create();
		} else {

			$tabunganCode	= "TF-".generatePaymentTransactionCode($memberId);

			if ($input["tabungan_tf_type"] == MemberTabunganTfType::TABUNGAN_TF_WD){
				$whereMemberId  = array("member_id" 	=> $memberId);
				$memberBankId	= current($this->CrudModel->gw("member_bank_account", $whereMemberId))->id;

				$data	= array("member_id" 			=> $memberId,
								"member_bank_id"		=> $memberBankId,
								"tabungan_tf_ammount"	=> $input["tabungan_tf_ammount"],
								"tabungan_tf_type"		=> $input["tabungan_tf_type"],
								"tabungan_tf_tr_code"	=> $tabunganCode,
								"tabungan_tf_approval"	=> MemberTabunganTfApproval::TABUNGAN_TF_NEW,
								"created_at"			=> date('Y-m-d H:i:s'),
								"created_by"			=> $memberId);

			} else {
				$data	= array("member_id" 			=> $memberId,
								"tabungan_tf_ammount"	=> $input["tabungan_tf_ammount"],
								"tabungan_tf_type"		=> $input["tabungan_tf_type"],
								"tabungan_tf_tr_code"	=> $tabunganCode,
								"tabungan_tf_approval"	=> MemberTabunganTfApproval::TABUNGAN_TF_NEW,
								"created_at"			=> date('Y-m-d H:i:s'),
								"created_by"			=> $memberId);
			}

			$updateStatus = $this->CrudModel->is("tabungan_member_transfer", $data);

			if ($updateStatus == "success"){

				$memberCode = $this->MemberModel->getMemberReferalCode($memberId);

				if ($input["tabungan_tf_type"] == MemberTabunganTfType::TABUNGAN_TF_WD){

					$whereMemberId  = array("member_id" 	=> $memberId);
					$memberBank		= current($this->CrudModel->gw("member_bank_account", $whereMemberId));
					$reportDescription  = "[".$memberCode."] Mengajukan Transfer Tabungan [".$tabunganCode."] ke ".$memberBank->bank_account_name." - ".$memberBank->bank_account_number;
					$notifDescription	= "[".$memberCode."] Mengajukan Transfer Tabungan [".$tabunganCode."] ke ".$memberBank->bank_account_name." - ".$memberBank->bank_account_number;

				} else {
					$reportDescription  = "[".$memberCode."] Mengajukan Transfer Tabungan [".$tabunganCode."] ke Wallet";
					$notifDescription	= "[".$memberCode."] Mengajukan Transfer Tabungan [".$tabunganCode."] ke Wallet";
				}

				$data	= array("member_id"				=> $memberId,
								"user_type"				=> CredentialType::MEMBER,
								"receiver"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"notif_description"		=> $notifDescription,
								"reference_link"		=> 'admin/tabungan/transfer-tabungan?type='. strtolower(MemberTabunganTfApproval::TABUNGAN_TF_NEW),
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);

				generalAllert("Pengajuan Berhasil!", "Pengajuan Transfer Tabungan Anda Akan Segera Diproses.", AllertType::SUCCESS);
			} else {
				generalAllert("Pengajuan Gagal !", "Terjadi kesalahan saat melakukan pengajuan transfer tabungan. Silahkan coba lagi", AllertType::FAILED);
			}

			$this->index();
		}
	}

	public function ammountValidation($params = null) {
		$memberId	= $this->session->userdata("member_id");
		$getAvailabelReferralCode = $this->CrudModel->gw('tabungan_Setting', array('id' => 1));
		if ( $params < current($getAvailabelReferralCode)->required_ammount) {
			$this->form_validation->set_message('ammountValidation', '{field} Minimal Rp.'. current($getAvailabelReferralCode)->required_ammount );
			return FALSE;
		} elseif ($params > current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance){
			$this->form_validation->set_message('ammountValidation', 'Saldo Tabungan Anda Tidak Cukup Untuk Melakukan Transaksi Ini');
			return FALSE;
		} else {
			return TRUE;
		}
	}

}
