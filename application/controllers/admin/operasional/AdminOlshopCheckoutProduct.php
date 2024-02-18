<?php defined('BASEPATH') OR exit('No direct script access allowed');


class AdminOlshopCheckoutProduct extends CI_Controller {

	function __construct(){
		parent::__construct();
		if($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		}
	}

	public function index(){
		$pageType	= $this->input->get('type');
		$content	= '_adminLayouts/checkoutProduct/index';

		switch ($pageType){
			case strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW):
				$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductList(NULL, MemberCheckoutProductApproval::CHECKOUT_NEW);
				$data	= array('session' 					=> SessionType::ADMIN,
								'pageType'					=> MemberCheckoutProductApproval::CHECKOUT_NEW,
								'csrfName'					=> $this->security->get_csrf_token_name(),
								'csrfToken'					=> $this->security->get_csrf_hash(),
								"dataCheckoutProduct"		=> $datacheckoutProduct,
								'content'					=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED):
				$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductList(NULL, MemberCheckoutProductApproval::CHECKOUT_PROCESSED);
				$data	= array('session' 					=> SessionType::ADMIN,
								'pageType'					=> MemberCheckoutProductApproval::CHECKOUT_PROCESSED,
								'csrfName'					=> $this->security->get_csrf_token_name(),
								'csrfToken'					=> $this->security->get_csrf_hash(),
								"dataCheckoutProduct"		=> $datacheckoutProduct,
								'content'					=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL):
				$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductList(NULL, MemberCheckoutProductApproval::CHECKOUT_CANCEL);
				$data	= array('session' 					=> SessionType::ADMIN,
								'pageType'					=> MemberCheckoutProductApproval::CHECKOUT_CANCEL,
								'csrfName'					=> $this->security->get_csrf_token_name(),
								'csrfToken'					=> $this->security->get_csrf_hash(),
								"dataCheckoutProduct"		=> $datacheckoutProduct,
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
		$this->form_validation->set_rules('checkout_approved_status', 'Status Checkout', 'trim|required');

		if ($this->form_validation->run() == false || $input["checkout_approved_status"] == MemberCheckoutProductApproval::CHECKOUT_NEW){
			switch ($input["checkout_approved_status"]){
				case MemberCheckoutProductApproval::CHECKOUT_NEW:
					generalAllert("Woops!", "Anda belum merubah status checkout",AllertType::INFO);
					break;
				default:
					generalAllert("Perubahan Status Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
					break;
			}
		} else {
			$adminId		= $this->session->userdata("admin_id");
			$approvedDate	= date('Y-m-d H:i:s');
			$params	= array("checkout_product_id"		=> $input["lentera_olshop_product_id"],
							"checkout_approved_status"	=> $input["checkout_approved_status"],
							"checkout_code"				=> $input["checkout_code"],
							"member_id"					=> $input["member_id"],
							"checkout_approved_by" 		=> $adminId,
							"checkout_approved_date"	=> $approvedDate,
							"updated_at"				=> $approvedDate,
							"updated_by"				=> $adminId);

			$updatedStatus	= $this->CheckoutProductModel->setCheckoutProductApproval($params);

			switch ($updatedStatus["status"]){
				case "success":
					generalAllert("Perubahan Status Berhasil!", $updatedStatus["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Perubahan Status Gagal!", $updatedStatus["data"], AllertType::FAILED);
			}

		}
		redirect('admin/checkout/product?type=' . strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW));
	}

}
