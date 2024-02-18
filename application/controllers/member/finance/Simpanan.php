<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class Simpanan extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {
		$memberId	= $this->session->userdata("member_id");
		$content	= '_memberLayouts/simpanan/index';
		$memberStatus 	= $this->MemberModel->getMemberActiveStatus($memberId);
		$data		= array('session' 						=> SessionType::MEMBER,
							'csrfName'						=> $this->security->get_csrf_token_name(),
							'csrfToken'						=> $this->security->get_csrf_hash(),
							'memberIsKyc'					=> $memberStatus["member_is_kyc"],
							'memberIsSimwa'					=> $memberStatus["member_is_simwa"],
							'memberIsSimpo'					=> $memberStatus["member_is_simpo"],
							'memberIsActivated'				=> $memberStatus["member_is_activated"],
							"dataMemberSimpananReport"		=> $this->SimpananModel->getAllSimpananAnggota($memberId),
							'dataContentSimpananFunding'	=> $this->SimpananModel->getSimpananContentMember($memberId, SimpananContentType::SIMPANAN_FUNDING),
							'dataContentSimpananAnggota'	=> $this->SimpananModel->getSimpananContentMember($memberId, SimpananContentType::SIMPANAN_KEANGGOTAAN),
							'content'						=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_transaction_code', 'Kode Transaksi', 'trim|required');

		if (($input['simpanan_input_type']) == "ammount"){
			$this->form_validation->set_rules('simpanan_member_ammount', 'Nominal', 'trim|required');
		};
		if (($input['simpanan_input_type']) == "month"){
			$this->form_validation->set_rules('simpanan_member_month', 'Jumlah Bulan', 'trim|required');
		};

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
								"simpanan_member_month"			=> $input["simpanan_member_month"] ?: NULL,
								"simpanan_input_type"			=> $input["simpanan_input_type"]);

			$setSimpananMember = $this->SimpananModel->setSimpananMember($params);


			switch ($setSimpananMember["status"]){
				case "success":
					generalAllert("Pembayaran Berhasil!", $setSimpananMember["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Pembayaran Gagal!", $setSimpananMember["data"], AllertType::FAILED);
			}
		}

		redirect('member/simpanan/index');

	}

}
