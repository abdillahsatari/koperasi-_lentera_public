<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminSimpananReport extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){

		$content= '_adminLayouts/simpananReport/index';
		$data	= array('session' 						=> SessionType::ADMIN,
						'dataAllSimpanan'				=> $this->SimpananModel->getAllSimpananAnggota(),
						'dataContentSimpananFunding'	=> $this->SimpananModel->reportSimpananContentFunding(),
						'dataContentSimpananAnggota'	=> $this->SimpananModel->reportSimpananContentAnggota(),
						'content'						=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

	public function show($type = NULL) {
		$content= '_adminLayouts/simpananReport/show';
		$data	= array('session' 					=> SessionType::ADMIN,
						'dataType'					=> urldecode($type),
						'dataSimpananMemberType'	=> $this->SimpananModel->reportDetailSimpananMember($type),
						'content'					=> $content);

		$this->load->view('_adminLayouts/wrapper', $data);
	}

}
