<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class Funding extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {
		$content	= '_memberLayouts/funding/index';
		$memberId		= $this->session->userdata('member_id');
		$whereId		= array('id'	=> $memberId);
		$getMember 		= $this->CrudModel->gw('member', $whereId);
		$memberStatus 	= $this->MemberModel->getMemberActiveStatus($memberId);

		$data		= array('session' 						=> SessionType::MEMBER,
							'dataMember'					=> $getMember,
							'memberIsKyc'					=> $memberStatus["member_is_kyc"],
							'memberIsSimwa'					=> $memberStatus["member_is_simwa"],
							'memberIsSimpo'					=> $memberStatus["member_is_simpo"],
							'memberIsActivated'				=> $memberStatus["member_is_activated"],
							'csrfName'						=> $this->security->get_csrf_token_name(),
							'csrfToken'						=> $this->security->get_csrf_hash(),
							'dataContentSimpananFunding'	=> $this->SimpananModel->getAllProgramFunding(),
							'content'						=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_transaction_code', 'Kode Transaksi', 'trim|required');
		$this->form_validation->set_rules('simpanan_member_ammount', 'Nominal', 'trim|required');

		if ($this->form_validation->run() == false) {
			generalAllert("Pembayaran Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
			$this->index();
		} else {

			$memberId	= $this->session->userdata("member_id");

			$params		= array("member_id"						=> $memberId,
								"member_transaction_code"		=> $input["member_transaction_code"],
								"lentera_simpanan_content_id"	=> $input["lentera_simpanan_content_id"],
								"lentera_simpanan_content_name"	=> $input["lentera_simpanan_content_name"],
								"simpanan_content_type"			=> $input["simpanan_content_type"],
								"simpanan_member_ammount"		=> $input["simpanan_member_ammount"] ?: NULL,
								"simpanan_input_type"			=> $input["simpanan_input_type"]);

			$setSimpananMember = $this->SimpananModel->setSimpananMember($params);

			switch ($setSimpananMember["status"]){
				case "success":
					$whereLscId		= array("id"	=> $input["lentera_simpanan_content_id"]);
					$dataLsc		= $this->CrudModel->gw("lentera_simpanan_content", $whereLscId);
					$lscName		= current($dataLsc)->simpanan_name;
					$lscLpointPercentage = current($dataLsc)->simpanan_lpoint_percentage;

					/* /cek simpanan has lpoint */
					if ($lscLpointPercentage){
						$lpointDebit 	= $input["simpanan_member_ammount"] * ($lscLpointPercentage / 100);
						$lpointTrCode	= "LP-".generatePaymentTransactionCode($memberId);
						$dataLpoint 	= array("member_id"						=> $memberId,
												"lentera_simpanan_content_id"	=> $input["lentera_simpanan_content_id"],
												"lentera_simpanan_content_name"	=> $lscName,
												"transaction_code"				=> $lpointTrCode,
												"lpoint_type"					=> MemberLpoinType::DEBIT,
												"lpoint_debit"					=> $lpointDebit,
												"created_at"					=> date('Y-m-d H:i:s'),
												"created_by"					=> $memberId);

						$statusAddLpoint = $this->CrudModel->is("member_lentera_point", $dataLpoint);

						if ($statusAddLpoint == "success"){

							$reportAmmount 	= number_format(intval($lpointDebit),0,'.','.');
							$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($memberId)."] Memperoleh Tambahan L-point [".$lpointTrCode."] Sebesar Rp.".$reportAmmount." Dari Pembayaran [".$lscName."]";
							$notifDescription	= " Anda Memperoleh Tambahan Rp.".$reportAmmount." L-point Dari Pembayaran [".$lscName."] Anda";

							$data	= array("member_id"				=> $memberId,
											"user_type"				=> CredentialType::AUTOMATIC_SYSTEM,
											"receiver"				=> CredentialType::MEMBER,
											"report_description"	=> $reportDescription,
											"notif_description"		=> $notifDescription,
											"reference_link"		=> 'member/dashboard',
											"created_at"			=> date('Y-m-d H:i:s'));

							$this->ActivityLog->actionLog($data);
						}
					}
					/* ./cek simpanan has lpoint */
					generalAllert("Pembayaran Berhasil!", $setSimpananMember["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Pembayaran Gagal!", $setSimpananMember["data"], AllertType::FAILED);
			}
		}

		redirect('member/simpanan/index');
	}

}
