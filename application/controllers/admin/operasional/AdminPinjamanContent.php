<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPinjamanContent extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){
		$query					= 'SELECT lentera_pinjaman_content.*, admin.admin_full_name FROM lentera_pinjaman_content JOIN admin ON lentera_pinjaman_content.created_by = admin.id';
		$dataContentPinjaman	= $this->CrudModel->q($query);
		$content				= '_adminLayouts/pinjamanContent/index';

		$data	= array('session' 				=> SessionType::ADMIN,
						'dataContentPinjaman'	=> $dataContentPinjaman,
						'content'				=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function create() {

		$content	= '_adminLayouts/pinjamanContent/create';

		$data 		= array('session' 		=> SessionType::ADMIN,
							'csrfName'		=> $this->security->get_csrf_token_name(),
							'csrfToken'		=> $this->security->get_csrf_hash(),
							'content'		=> $content);

		$this->load->view("_adminLayouts/wrapper", $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('pinjaman_name', 'Nama program Simpanan', 'trim|required');
		$this->form_validation->set_rules('pinjaman_content_status', 'Status Program', 'trim|required');

		if ($this->form_validation->run() == false){
			generalAllert("Gagal!". "<br/>", validation_errors()." Silahkan coba lagi. Pastikan semua inputan anda telah sesuai", AllertType::FAILED);
			$this->create();
		} else {
			$dataSimpananContent	= array("Pinjaman_name"					=> $input["pinjaman_name"],
											"pinjaman_content_status"		=> $input["pinjaman_content_status"] ?: NULL,
											'pinjaman_content_tenor_type'	=> $input["pinjaman_content_tenor_type"],
											"created_at"					=> date('Y-m-d H:i:s'),
											"created_by"					=> $this->session->userdata("admin_id"));

			$insertedContent = $this->CrudModel->i2("lentera_pinjaman_content", $dataSimpananContent);

			$totalTenorSetting = count($input["pinjaman_bunga"]);
			for($i=0; $i<$totalTenorSetting; $i++){
				$data	= array('pinjaman_content_id'			=> $insertedContent,
								'pinjaman_content_bunga'		=> $input["pinjaman_bunga"][$i],
								'pinjaman_content_tenor'		=> $input["pinjaman_tenor"][$i],
								"created_at"					=> date('Y-m-d H:i:s'),
								"created_by"					=> $this->session->userdata("admin_id"));

				$insertedDetail = $this->CrudModel->is("lentera_pinjaman_detail",$data);
			}

			if ($insertedDetail == 'success') {

				$reportDescription  = "Membuat program pinjaman baru";
				$dataActionLog		= array("admin_id"				=> $this->session->userdata("admin_id"),
											"user_type"				=> CredentialType::ADMIN,
											"report_description"	=> $reportDescription,
											"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($dataActionLog);
				generalAllert("Berhasil!", "Program pinjaman baru telah berhasil ditambahkan.", AllertType::SUCCESS);
			} else {
				generalAllert("Gagal!", "Terjadi kesalahan saat melakukan penambahan program pinjaman baru. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/pinjaman/content/index');
		}
	}

	public function edit($id){
		$content				= '_adminLayouts/pinjamanContent/edit';
		$dataContentPinjaman 	= $this->PinjamanModel->getPinjamanContentDetail($id);

		$data 		= array('session' 				=> SessionType::ADMIN,
							'csrfName'				=> $this->security->get_csrf_token_name(),
							'csrfToken'				=> $this->security->get_csrf_hash(),
							'dataPinjamanContent'	=> current($dataContentPinjaman),
							'content'				=> $content);

		$this->load->view("_adminLayouts/wrapper", $data);
	}

	public function update(){

		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('pinjaman_name', 'Nama program Simpanan', 'trim|required');
		$this->form_validation->set_rules('pinjaman_content_status', 'Status Program', 'trim|required');

		if ($this->form_validation->run() == false){
			generalAllert("Gagal!". "<br/>", validation_errors()." Silahkan coba lagi. Pastikan semua inputan anda telah sesuai", AllertType::FAILED);
			$this->edit($input["content_pinjaman_id"]);
		} else {
			$whereContentId			= array("id" => $input["content_pinjaman_id"]);
			$dataSimpananContent	= array("Pinjaman_name"					=> $input["pinjaman_name"],
											"pinjaman_content_status"		=> $input["pinjaman_content_status"] ?: NULL,
											'pinjaman_content_tenor_type'	=> $input["pinjaman_content_tenor_type"],
											"updated_at"					=> date('Y-m-d H:i:s'),
											"updated_by"					=> $this->session->userdata("admin_id"));

			$updatedStatus = $this->CrudModel->ud("lentera_pinjaman_content", $dataSimpananContent, $whereContentId);

			$totalCurrentTenorSetting 	= count($input["current_pinjaman_bunga"]);
			$totalNewTenorSetting 		= count($input["pinjaman_bunga"]);

			for($i=0; $i<$totalCurrentTenorSetting; $i++){
				$data	= array('pinjaman_content_bunga'		=> $input["current_pinjaman_bunga"][$i],
								'pinjaman_content_tenor'		=> $input["current_pinjaman_tenor"][$i],
								"created_at"					=> date('Y-m-d H:i:s'),
								"created_by"					=> $this->session->userdata("admin_id"));

				$whereContentDetailId = array("id" => $input["content_pinjaman_detail_id"][$i] );
				$this->CrudModel->u("lentera_pinjaman_detail",$data, $whereContentDetailId);
			}


			for($i=0; $i<$totalNewTenorSetting; $i++){
				$data	= array('pinjaman_content_id'			=> $input["content_pinjaman_id"],
								'pinjaman_content_bunga'		=> $input["pinjaman_bunga"][$i],
								'pinjaman_content_tenor'		=> $input["pinjaman_tenor"][$i],
								"created_at"					=> date('Y-m-d H:i:s'),
								"created_by"					=> $this->session->userdata("admin_id"));

				$whereContentDetailId = array("id" => $input["content_pinjaman_detail_id"][$i] );
				$this->CrudModel->i("lentera_pinjaman_detail",$data, $whereContentDetailId);
			}

			if ($updatedStatus == 'success') {

				$reportDescription  = "Merubah pengaturan program Pinjaman [".$input["pinjaman_name"]."]";
				$dataActionLog		= array("admin_id"				=> $this->session->userdata("admin_id"),
											"user_type"				=> CredentialType::ADMIN,
											"report_description"	=> $reportDescription,
											"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($dataActionLog);
				generalAllert("Berhasil!", "Pengaturan program pinjaman telah berhasil dirubah.", AllertType::SUCCESS);
			} else {
				generalAllert("Gagal!", "Terjadi kesalahan saat melakukan perubahan pengaturan program pinjaman. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/pinjaman/content/index');
		}

	}

}
