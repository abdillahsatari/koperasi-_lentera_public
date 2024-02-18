<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminSimpananContent extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){
		$query					= 'SELECT lentera_simpanan_content.*, admin.admin_full_name FROM lentera_simpanan_content JOIN admin ON lentera_simpanan_content.created_by = admin.id';
		$dataContentSimpanan	= $this->CrudModel->q($query);
		$content				= '_adminLayouts/simpananContent/index';

		$data	= array('session' 				=> SessionType::ADMIN,
						'dataContentSimpanan'	=> $dataContentSimpanan,
						'content'				=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function create() {

		$content	= '_adminLayouts/simpananContent/create';

		$data 		= array('session' 		=> SessionType::ADMIN,
							'csrfName'		=> $this->security->get_csrf_token_name(),
							'csrfToken'		=> $this->security->get_csrf_hash(),
							'content'		=> $content);

		$this->load->view("_adminLayouts/wrapper", $data);
	}

	public function save(){
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('simpanan_name', 'Nama program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_minimum_price', 'Minimal Pembayaran', 'trim|required');
		$this->form_validation->set_rules('simpanan_duration', 'Durasi Program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_content_status', 'Status Program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_content_type', 'Tipe Program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_balas_jasa_percentage', 'Persentase Balas Jasa', 'trim|required');
		$this->form_validation->set_rules('simpanan_balas_jasa_perdays', 'Durasi Pembayaran Balas Jasa', 'trim|required');

		if ($this->form_validation->run() == false){
			$this->create();
		} else {
			$dataSimpananContent	= array("simpanan_name"					=> $input["simpanan_name"],
											"simpanan_minimum_price" 		=> $input["simpanan_minimum_price"] ?: NULL,
											"simpanan_maximum_price" 		=> $input["simpanan_maximum_price"] ?: NULL,
											"simpanan_duration"				=> $input["simpanan_duration"],
											"simpanan_content_status"		=> $input["simpanan_content_status"] ?: NULL,
											"simpanan_content_type"			=> $input["simpanan_content_type"] ?: NULL,
											"simpanan_balas_jasa_percentage"=> $input["simpanan_balas_jasa_percentage"] ?: NULL,
											"simpanan_balas_jasa_perdays"	=> $input["simpanan_balas_jasa_perdays"],
											"simpanan_fixed_price"			=> $input["simpanan_fixed_price"] ?: NULL,
											"simpanan_promo_cashback_percentage"		=> $input["simpanan_promo_cashback"],
											"simpanan_is_promo_active"		=> $input["simpanan_is_promo_active"] ?: NULL,
											"simpanan_lpoint_percentage"	=> $input["simpanan_lpoint_percentage"] ?: NULL,
											"created_at"					=> date('Y-m-d H:i:s'),
											"created_by"					=> $this->session->userdata("admin_id"));

			$insertedContent = $this->CrudModel->is("lentera_simpanan_content", $dataSimpananContent);

			if ($insertedContent == 'success') {
				$reportDescription  = "Membuat Program Simpanan Baru [".$input["simpanan_name"]."]";
				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);
				generalAllert("Berhasil!", "Program Simpanan baru telah berhasil ditambahkan.", AllertType::SUCCESS);
			} else {
				generalAllert("Gagal!", "Terjadi kesalahan saat melakukan penambahan program simpanan baru. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/simpanan/content/index');
		}
	}

	public function edit($id){
		$content			= '_adminLayouts/simpananContent/edit';
		$contentId			= array("id" => $id);
		$dataSimpananContent= $this->CrudModel->gw("lentera_simpanan_content", $contentId);

		$data 		= array('session' 				=> SessionType::ADMIN,
							'csrfName'				=> $this->security->get_csrf_token_name(),
							'csrfToken'				=> $this->security->get_csrf_hash(),
							'dataSimpananContent'	=> current($dataSimpananContent),
							'content'				=> $content);

		$this->load->view("_adminLayouts/wrapper", $data);
	}

	public function update(){

		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('simpanan_name', 'Nama program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_minimum_price', 'Minimal Pembayaran', 'trim|required');
		$this->form_validation->set_rules('simpanan_duration', 'Durasi Program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_content_status', 'Status Program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_content_type', 'Tipe Program Simpanan', 'trim|required');
		$this->form_validation->set_rules('simpanan_balas_jasa_percentage', 'Persentase Balas Jasa', 'trim|required');
		$this->form_validation->set_rules('simpanan_balas_jasa_perdays', 'Durasi Pembayaran Balas Jasa', 'trim|required');

		if ($this->form_validation->run() == false){
			$this->edit($input["simpanan_content_id"]);
		} else {
			$dataSimpananContent	= array("simpanan_name"					=> $input["simpanan_name"],
											"simpanan_minimum_price" 		=> $input["simpanan_minimum_price"] ?: NULL,
											"simpanan_maximum_price" 		=> $input["simpanan_maximum_price"] ?: NULL,
											"simpanan_duration"				=> $input["simpanan_duration"],
											"simpanan_content_status"		=> $input["simpanan_content_status"] ?: NULL,
											"simpanan_content_type"			=> $input["simpanan_content_type"] ?: NULL,
											"simpanan_balas_jasa_percentage"=> $input["simpanan_balas_jasa_percentage"] ?: NULL,
											"simpanan_balas_jasa_perdays"	=> $input["simpanan_balas_jasa_perdays"],
											"simpanan_fixed_price"			=> $input["simpanan_fixed_price"] ?: NULL,
											"simpanan_promo_cashback_percentage"		=> $input["simpanan_promo_cashback"],
											"simpanan_is_promo_active"		=> $input["simpanan_is_promo_active"] ?: NULL,
											"simpanan_lpoint_percentage"	=> $input["simpanan_lpoint_percentage"] ?: NULL,
											"updated_at"					=> date('Y-m-d H:i:s'),
											"updated_by"					=> $this->session->userdata("admin_id"));

			$whereId			= array("id" => $input["simpanan_content_id"]);
			$insertedContent 	= $this->CrudModel->ud("lentera_simpanan_content", $dataSimpananContent, $whereId);

			if ($insertedContent == 'success') {
				$reportDescription  = "Merubah Pengaturan Simpanan [".$input["simpanan_name"]."]";
				$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);
				generalAllert("Berhasil!", "Program Simpanan telah berhasil dirubah.", AllertType::SUCCESS);
			} else {
				generalAllert("Gagal!", "Terjadi kesalahan saat melakukan perubahan program simpanan. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/simpanan/content/index');
		}
	}

}
