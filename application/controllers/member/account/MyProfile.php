<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyProfile extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED){
			redirect('member/login');
		}
	}

	public function index() {

		$content 	= '_memberLayouts/profile/index';
		$query		= 'SELECT * FROM member JOIN member_kyc_info ON member.member_kyc_id = member_kyc_info.id WHERE member.id = '.$this->session->userdata("member_id").' ';
		$dataMember	= $this->CrudModel->q($query);
		$data 		= array('session'     	=> SessionType::MEMBER,
							'dataMember'	=> $dataMember,
							'appProfile'	=> true,
							'content'		=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function edit($id){
		$content 	= '_memberLayouts/profile/edit';
		$query		= 'SELECT * FROM member JOIN member_kyc_info ON member.member_kyc_id = member_kyc_info.id WHERE member.id = '.$id.' ';
		$dataMember	= $this->CrudModel->q($query);
		$data 		= array('session'     	=> SessionType::MEMBER,
							'csrfName'		=> $this->security->get_csrf_token_name(),
							'csrfToken'		=> $this->security->get_csrf_hash(),
							'dataMember'	=> $dataMember,
							'appProfile'	=> true,
							'content'		=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function update() {
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('member_phone_number', 'No. Hp', 'trim|required');
		$this->form_validation->set_rules('member_full_name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('member_ktp_number', 'No. Ktp', 'trim|required');
		$this->form_validation->set_rules('member_ktp_image', 'Gambar Ktp', 'trim|required');
		$this->form_validation->set_rules('member_birth_place', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('member_birth_date', 'Tgl Lahir', 'trim|required');
		$this->form_validation->set_rules('member_last_education', 'Pendidikan Terakhir', 'trim|required');
		$this->form_validation->set_rules('member_job', 'Pekerjaan', 'trim|required');
		$this->form_validation->set_rules('member_address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('member_kelurahan', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('member_kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('member_kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('member_provinsi', 'Provinsi', 'trim|required');
		$this->form_validation->set_rules('member_kode_pos', 'Kode Pos', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->edit($this->session->userdata("member_id"));
		} else {

			$whereSimpo	= array("id" => $this->session->userdata("member_id"),"member_is_simpo" => true);
			$whereSimwa	= array("id" => $this->session->userdata("member_id"),"member_is_simwa" => true);

			$memberSimpoStatus = $this->CrudModel->cw("member", $whereSimpo);
			$memberSimwaStatus = $this->CrudModel->cw("member", $whereSimwa);

			if ($memberSimpoStatus > 0 && $memberSimwaStatus > 0){
				$memberIsActivated	= TRUE;
			}

			$dataMember	= array("member_email"			=> $input["member_email"],
								"member_phone_number" 	=> $input["member_phone_number"],
								"member_full_name"		=> $input["member_full_name"],
								"member_is_kyc"			=> TRUE,
								"member_is_activated"	=> isset($memberIsActivated) ?: null,
								"updated_at"			=> date('Y-m-d H:i:s'),
								"updated_by"			=> $this->session->userdata("member_id"));

			$dataKyc	= array("member_ktp_number"		=> $input["member_ktp_number"],
								"member_ktp_image" 		=> $input["member_ktp_image"],
								"member_birth_place"	=> $input["member_birth_place"],
								"member_birth_date"		=> $input["member_birth_date"],
								"member_last_education"	=> $input["member_last_education"],
								"member_job"			=> $input["member_job"],
								"member_address"		=> $input["member_address"],
								"member_kelurahan"		=> $input["member_kelurahan"],
								"member_kecamatan"		=> $input["member_kecamatan"],
								"member_kota"			=> $input["member_kota"],
								"member_provinsi"		=> $input["member_provinsi"],
								"member_kode_pos"		=> $input["member_kode_pos"],
								"updated_at"			=> date('Y-m-d H:i:s'),
								"updated_by"			=> $this->session->userdata("member_id"));

			$memberId 		= $this->session->userdata("member_id");
			$whereMemberId	= array("id" => $memberId);
			$whereKycId		= array("id" => $input["member_kyc_id"]);

			$this->CrudModel->u("member", $dataMember, $whereMemberId);
			$this->CrudModel->u("member_kyc_info", $dataKyc, $whereKycId);

			$this->session->set_userdata('member_full_name', $input["member_full_name"]);
			$this->session->set_userdata("member_is_kyc", true);

			$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($memberId)."] Update Profile";

			$data	= array("member_id"				=> $memberId,
							"user_type"				=> CredentialType::MEMBER,
							"report_description"	=> $reportDescription,
							"created_at"			=> date('Y-m-d H:i:s'));

			$this->ActivityLog->actionLog($data);

			redirect('member-profile');
		}
	}
}
