<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPinjamanAnggota extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){

		$pageType		= urldecode($this->uri->segment(3));
		$content		= '_adminLayouts/pinjamanAnggota/index';

		switch ($pageType){
			case 'permohonan-pinjaman':
				$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanSummary();
				$memberPinjamanList	= $this->PinjamanModel->getMemberPinjamanList(NULL, MemberPinjamanApproval::PINJAMAN_NEW);
				$data				= array('session' 					=> SessionType::MEMBER,
											'pageType'					=> "Permohonan Pengajuan Pinjaman",
											'csrfName'					=> $this->security->get_csrf_token_name(),
											'csrfToken'					=> $this->security->get_csrf_hash(),
											"dataMemberPinjamanList"	=> $memberPinjamanList,
											'dataAllPinjaman'			=> current($dataPinjamanMember)->countAllPinjaman,
											'dataPaidPinjaman'			=> current($dataPinjamanMember)->countPaidPinjaman,
											'content'					=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case 'semua-pinjaman':
				$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanSummary();
				$memberPinjamanList	= $this->PinjamanModel->getMemberPinjamanList();
				$data				= array('session' 					=> SessionType::MEMBER,
											'pageType'					=> "Semua Pinjaman",
											'csrfName'					=> $this->security->get_csrf_token_name(),
											'csrfToken'					=> $this->security->get_csrf_hash(),
											"dataMemberPinjamanList"	=> $memberPinjamanList,
											'dataAllPinjaman'			=> current($dataPinjamanMember)->countAllPinjaman,
											'content'					=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case 'pembayaran-pinjaman':
				$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanSummary();
				$memberPinjamanList	= $this->PinjamanModel->getMemberPinjamanList(NULL, MemberPinjamanStatus::PINJAMAN_PAID);
				$data				= array('session' 					=> SessionType::MEMBER,
											'pageType'					=> "Semua Pembayaran Pinjaman",
											'csrfName'					=> $this->security->get_csrf_token_name(),
											'csrfToken'					=> $this->security->get_csrf_hash(),
											"dataMemberPinjamanList"	=> $memberPinjamanList,
											'dataPaidPinjaman'			=> current($dataPinjamanMember)->countPaidPinjaman,
											'content'					=> $content);
				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			default:
				$this->load->view('errors/html/error_404');
				break;

		}
	}

	public function update(){
		$input 		= $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('pinjaman_status_approval', 'Status Pinjaman', 'trim|required');

		if ($this->form_validation->run() == false || $input["pinjaman_status_approval"] == MemberPinjamanApproval::PINJAMAN_NEW){
			switch ($input["pinjaman_status_approval"]){
				case MemberPinjamanApproval::PINJAMAN_NEW:
					generalAllert("Woops!", "Anda belum merubah status pinjaman",AllertType::INFO);
					break;
				default:
					generalAllert("Perubahan Status Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
					break;
			}
		} else {
			$params	= array("member_id" 				=> $input["member_id"],
							"pinjaman_id"				=> $input["pinjaman_id"],
							"pinjaman_transaction_code"	=> $input["pinjaman_transaction_code"],
							"pinjaman_ammount"			=> $input["pinjaman_ammount"],
							"pinjaman_tenor"			=> $input["pinjaman_tenor"],
							"pinjaman_bunga"			=> $input["pinjaman_bunga"],
							"pinjaman_tenor_type"		=> $input["pinjaman_tenor_type"],
							"pinjaman_status_approval"	=> $input["pinjaman_status_approval"]);

			$updatedStatus	= $this->PinjamanModel->setPinjamanMemberDetail($params);

			switch ($updatedStatus["status"]){
				case "success":
					generalAllert("Perubahan Status Berhasil!", $updatedStatus["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Perubahan Status Gagal!", $updatedStatus["data"], AllertType::FAILED);
			}

		}
		redirect('admin/pinjaman/permohonan-pinjaman');
	}

	public function show($pinjamanId = NULL) {

		$content			= '_adminLayouts/pinjamanAnggota/show';
		$dataPinjamanMember	= $this->PinjamanModel->getMemberPinjamanDetailSummary($pinjamanId);
		$memberPinjamanDetailList	= $this->PinjamanModel->getMemberPinjamanDetailList($pinjamanId);
		$data				= array('session' 						=> SessionType::MEMBER,
									"dataMemberPinjamanDetailList"	=> $memberPinjamanDetailList,
									'dataPaidPinjaman'				=> current($dataPinjamanMember)->countPaidPinjaman,
									'dataUnpaidPinjaman'			=> current($dataPinjamanMember)->countUnpaidPinjaman,
									'content'						=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

}
