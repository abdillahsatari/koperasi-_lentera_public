<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminOlshop extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index() {

		$content 			= '_adminLayouts/olshop/index';
		$dataOlshopProduct	= $this->CrudModel->ga("lentera_olshop_product");
		$data 	= array('session'			=> SessionType::ADMIN,
						'dataOlshopProduct'	=> $dataOlshopProduct,
						'content'			=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function create(){
		$content 	= '_adminLayouts/olshop/create';
		$data 		= array('session'			=> SessionType::ADMIN,
							'csrfName'			=> $this->security->get_csrf_token_name(),
							'csrfToken'			=> $this->security->get_csrf_hash(),
							'content'			=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function save(){
		$input	= $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('olshop_product_name', 'Nama Produk', 'trim|required');
		$this->form_validation->set_rules('olshop_product_price', 'Harga L-poin', 'trim|required');
		$this->form_validation->set_rules('olshop_product_status', 'Status Produk', 'trim|required');
		$this->form_validation->set_rules('olshop_product_img', 'Thumbnail Produk', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->create();
		} else {

			$dataolshopProduct	= array("olshop_product_name"	=> ucfirst($input["olshop_product_name"]),
										"olshop_product_price" 	=> $input["olshop_product_price"],
										"olshop_product_status"	=> $input["olshop_product_status"],
										"olshop_product_img"	=> $input["olshop_product_img"],
										"created_at"			=> date('Y-m-d H:i:s'),
										"created_by"			=> $this->session->userdata("admin_id"));

			$insertedProduct = $this->CrudModel->is("lentera_olshop_product", $dataolshopProduct);

			if ($insertedProduct == 'success') {
				generalAllert("Tambah Produk Berhasil!", "Produk baru telah berhasil ditambahkan.", AllertType::SUCCESS);
			} else {
				generalAllert("Tambah Produk Gagal!", "Terjadi kesalahan saat melakukan penambahan produk. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/olshop/index');
		}
	}

	public function edit($id){
		$content 	= '_adminLayouts/olshop/edit';
		$whereId	= array("id" => $id);
		$dataProduct= $this->CrudModel->gw("lentera_olshop_product", $whereId);
		$data 		= array('session'			=> SessionType::ADMIN,
							'csrfName'			=> $this->security->get_csrf_token_name(),
							'csrfToken'			=> $this->security->get_csrf_hash(),
							'dataProduct'		=> current($dataProduct),
							'content'			=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function update(){
		$input	= $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('olshop_product_name', 'Nama Produk', 'trim|required');
		$this->form_validation->set_rules('olshop_product_price', 'Harga L-poin', 'trim|required');
		$this->form_validation->set_rules('olshop_product_status', 'Status Produk', 'trim|required');
		$this->form_validation->set_rules('olshop_product_img', 'Thumbnail Produk', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->edit($input["product_id"]);
		} else {

			$dataolshopProduct	= array("olshop_product_name"	=> ucfirst($input["olshop_product_name"]),
										"olshop_product_price" 	=> $input["olshop_product_price"],
										"olshop_product_status"	=> $input["olshop_product_status"],
										"olshop_product_img"	=> $input["olshop_product_img"],
										"created_at"			=> date('Y-m-d H:i:s'),
										"created_by"			=> $this->session->userdata("admin_id"));

			$whereId			= array("id" => $input["product_id"]);
			$updatedProduct 	= $this->CrudModel->ud("lentera_olshop_product", $dataolshopProduct, $whereId);

			if ($updatedProduct == 'success') {
				generalAllert("Edit Produk Berhasil!", "Informasi produk telah berhasil diubah.", AllertType::SUCCESS);
			} else {
				generalAllert("Edit Produk Gagal!", "Terjadi kesalahan saat melakukan perubahan informasi produk. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			redirect('admin/olshop/index');
		}
	}

}
