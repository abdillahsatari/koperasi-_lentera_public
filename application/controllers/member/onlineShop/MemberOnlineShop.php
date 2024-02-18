<?php
defined('BASEPATH') OR exit ('No direct access allowed');

class MemberOnlineShop extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('member_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('member/login');
		}
	}

	public function index() {

		$content= '_memberLayouts/onlineShop/index';
		$whereStatus	= array("olshop_product_status" => true);
		$dataOnlineShop	= $this->CrudModel->gw("lentera_olshop_product", $whereStatus);
		$data	= array('session' 			=> SessionType::MEMBER,
						'csrfName'			=> $this->security->get_csrf_token_name(),
						'csrfToken'			=> $this->security->get_csrf_hash(),
						'dataOnlineShop'	=> $dataOnlineShop,
						'content'			=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	public function save() {
		$input = $this->input->post(NULL, TRUE);

		$this->form_validation->set_rules('member_transaction_code', 'Kode Transaksi', 'trim|required');
		$this->form_validation->set_rules('checkout_quantity', 'Kuantitas Produk', 'trim|required');
		$this->form_validation->set_rules('lentera_olshop_product_price', 'Harga Produk', 'trim|callback_lpointBalancelValidation['.$input["checkout_quantity"].']');

		if ($this->form_validation->run() == false) {
			generalAllert("Pembayaran Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
			$this->index();
		} else {
			$memberId		= $this->session->userdata("member_id");
			$checkoutCode	= "CO-".generatePaymentTransactionCode($memberId);
			$params			= array("member_id" 				=> $memberId,
									"member_transaction_code" 	=> $input["member_transaction_code"],
									"olshop_product_id"			=> $input["lentera_olshop_product_id"],
									"olshop_product_price"		=> $input["lentera_olshop_product_price"],
									"checkout_code"				=> $checkoutCode,
									"checkout_quantity"			=> $input["checkout_quantity"],
									"created_at"				=> date('Y-m-d H:i:s'),
									"created_by"				=> $memberId);

			$setCheckoutProduct = $this->CheckoutProductModel->setCheckoutProduct($params);

			switch ($setCheckoutProduct["status"]){
				case "success":
					generalAllert("Pembelian Produk Berhasil!", $setCheckoutProduct["data"], AllertType::SUCCESS);
					break;
				default:
					generalAllert("Pembelian Produk Gagal!", $setCheckoutProduct["data"], AllertType::FAILED);
					break;

			}

		}

		redirect('member/onlineShop/index');
	}

	public function lpointBalancelValidation($field = null, $params = null) {
		$totalLpointBalance = current($this->FinanceModel->memberFinance($this->session->userdata("member_id")))->totalLpointBalance;
		if ($totalLpointBalance > $field * $params) {
			return TRUE;
		} else {
			$this->form_validation->set_message('lpointBalancelValidation', 'Saldo L-point Anda Tidak Cukup Untuk Melakukan Pembelian Produk Ini. Silahkan Melakukan Pembayaran Simpanan Micro ataupun Simpanan Macro Untuk Menambah Saldo L-point Anda');
			return FALSE;
		}
	}

}
