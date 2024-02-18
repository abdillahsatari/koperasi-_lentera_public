<?php defined('BASEPATH') OR exit('No direct script access allowed');


class MemberOlshopCheckoutProduct extends CI_Controller {

	function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index(){
		$pageType	= $this->input->get('type');
		$content	= '_memberLayouts/checkoutProduct/index';
		$memberId	= $this->session->userdata("member_id");

		switch ($pageType){
			case strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW):
				$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductList($memberId, MemberCheckoutProductApproval::CHECKOUT_NEW);
				$data	= array('session' 					=> SessionType::MEMBER,
								'pageType'					=> MemberCheckoutProductApproval::CHECKOUT_NEW,
								'csrfName'					=> $this->security->get_csrf_token_name(),
								'csrfToken'					=> $this->security->get_csrf_hash(),
								"dataCheckoutProduct"		=> $datacheckoutProduct,
								'content'					=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			case strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED):
				$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductList($memberId, MemberCheckoutProductApproval::CHECKOUT_PROCESSED);
				$data	= array('session' 					=> SessionType::MEMBER,
								'pageType'					=> MemberCheckoutProductApproval::CHECKOUT_PROCESSED,
								'csrfName'					=> $this->security->get_csrf_token_name(),
								'csrfToken'					=> $this->security->get_csrf_hash(),
								"dataCheckoutProduct"		=> $datacheckoutProduct,
								'content'					=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			case strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL):
				$datacheckoutProduct	= $this->CheckoutProductModel->getCheckoutProductList($memberId, MemberCheckoutProductApproval::CHECKOUT_CANCEL);
				$data	= array('session' 					=> SessionType::MEMBER,
								'pageType'					=> MemberCheckoutProductApproval::CHECKOUT_CANCEL,
								'csrfName'					=> $this->security->get_csrf_token_name(),
								'csrfToken'					=> $this->security->get_csrf_hash(),
								"dataCheckoutProduct"		=> $datacheckoutProduct,
								'content'					=> $content);

				$this->load->view('_memberLayouts/wrapper', $data);
				break;
			default:
				$this->load->view('errors/html/error_404');
				break;
		}
	}
}
