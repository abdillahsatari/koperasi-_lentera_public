<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MemberRegulation extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED){
			redirect('member/login');
		}
	}

	public function index() {

		$pageType 		= urldecode($this->uri->segment(3));
		$content		= '_memberLayouts/regulationInfo/index';
		$dataRegulation	= $this->CrudModel->ga("admin_setting_company");

		$data	= array('session' 		=> SessionType::ADMIN,
						'pageType'		=> $pageType,
						'dataRegulation'=> current($dataRegulation),
						'csrfName'		=> $this->security->get_csrf_token_name(),
						'csrfToken'		=> $this->security->get_csrf_hash(),
						'content'		=> $content);


		if ($pageType == "aturan" || $pageType == "ad-art") {
			$this->load->view('_memberLayouts/wrapper', $data);
		} else {
			$this->load->view('errors/html/error_404');
		}
	}
}
