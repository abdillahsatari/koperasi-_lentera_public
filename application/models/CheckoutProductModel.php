<?php

class CheckoutProductModel extends CrudModel {

	/**
	 *
	 *	Getter
	 *
	 **/

	public function getCheckoutProductList($memberId = NULL, $approvedStatus = NULL){

		if ($memberId){
			$query = 'SELECT MCP.*, ME.member_full_name, ME.member_phone_number, ME.member_referal_code, LOP.olshop_product_name, LOP.olshop_product_price 
						FROM member_checkout_product MCP
						JOIN member ME ON MCP.member_id = ME.id
						JOIN lentera_olshop_product LOP ON MCP.olshop_product_id = LOP.id
						WHERE ME.id = "'.$memberId.'" AND MCP.checkout_approved_status = "'.$approvedStatus.'"
						ORDER BY MCP.created_at DESC';
		} else {
			$query = 'SELECT MCP.*, ME.member_full_name, ME.member_phone_number, ME.member_referal_code, LOP.olshop_product_name, LOP.olshop_product_price
						FROM member_checkout_product MCP
						JOIN member ME ON MCP.member_id = ME.id
						JOIN lentera_olshop_product LOP ON MCP.olshop_product_id = LOP.id
						WHERE MCP.checkout_approved_status = "'.$approvedStatus.'"
						ORDER BY MCP.created_at DESC';
		}

		return $this->q($query);

	}

	public function getCheckoutProductDetail($id){
		$query = 'SELECT MCP.*, ME.member_full_name, ME.member_phone_number, ME.member_referal_code, ME.member_email, LOP.olshop_product_name, LOP.olshop_product_price 
						FROM member_checkout_product MCP
						JOIN member ME ON MCP.member_id = ME.id
						JOIN lentera_olshop_product LOP ON MCP.olshop_product_id = LOP.id
						WHERE MCP.id = "'.$id.'"';

		return $this->q($query);
	}

	/**
	 *
	 *	Setter
	 *
	 **/

	public function setCheckoutProduct($params){
		$authService = authenticateMemberTrCode($params["member_id"], $params["member_transaction_code"]);

		switch ($authService["status"]){
			case AuthStatus::AUTHORIZED:
				$params			= array("member_id" 				=> $params["member_id"],
										"olshop_product_id"			=> $params["olshop_product_id"],
										"olshop_product_price"		=> $params["olshop_product_price"],
										"checkout_code"				=> $params["checkout_code"],
										"checkout_quantity"			=> $params["checkout_quantity"],
										"checkout_approved_status"	=> MemberCheckoutProductApproval::CHECKOUT_NEW,
										"created_at"				=> $params["created_at"],
										"created_by"				=> $params["created_by"]);

				$checkoutStatus	= $this->is("member_checkout_product", $params);

				if ($checkoutStatus == "success"){
					$checkoutInsertedId	= $this->db->insert_id();
					$lpintCredit		= $params["olshop_product_price"] * $params["checkout_quantity"];
					$lpointTrCode		= "LP-".generatePaymentTransactionCode($params["member_id"]);
					$dataLpoint 		= array("member_id"						=> $params["member_id"],
												"lentera_checkout_product_id"	=> $checkoutInsertedId,
												"transaction_code"				=> $lpointTrCode,
												"lpoint_type"					=> MemberLpoinType::CREDIT,
												"lpoint_credit"					=> $lpintCredit,
												"created_at"					=> $params["created_at"],
												"created_by"					=> $params["member_id"]);

					$insertedLpointStatus = $this->is("member_lentera_point", $dataLpoint);

					if ($insertedLpointStatus == "success") {

						//send report
						$data 		= array("transaction_id"		=> $checkoutInsertedId,
											"transaction_input_type"=> TransactionInputType::CREDIT,
											"transaction_type"		=> TransactionType::LPOINT_CREDIT);

						$this->ActivityLog->transactionLog($data);

						$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membeli Produk di Toko Online";
						$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membeli Produk di Toko Online";
						$data	= array("member_id"				=> $params["member_id"],
										"user_type"				=> CredentialType::MEMBER,
										"receiver"				=> CredentialType::ADMIN,
										"report_description"	=> $reportDescription,
										"notif_description"		=> $notifDescription,
										"reference_link"		=> 'admin/checkout/product?type='. strtolower(MemberCheckoutProductApproval::CHECKOUT_NEW),
										"created_at"			=> date('Y-m-d H:i:s'));

						$this->ActivityLog->actionLog($data);

						$result = array("status" 	=> "success",
										"data"		=> "Terima kasih telah melakukan pembelian produk pada toko online Lentera Digital Indonesia.");
					} else {

						$whereId	= array("id" => $checkoutInsertedId);
						$this->d("member_checkout_product", $whereId);

						$result = array("status" 	=> "failed",
										"data"		=> "Terjadi kesalahan saat melakukan pembelian produk. Silahkan menghubungi pengurus");
					}
				} else {
					$result = array("status" 	=> "failed",
									"data"		=> "Terjadi kesalahan saat melakukan pembelian produk. Silahkan menghubungi pengurus");
				}

				break;
			default:
				$result = array("status" 	=> "failed",
								"data"		=> "Mohon periksa kembali kode transaksi anda");
				break;
		}

		return $result;
	}

	public function setCheckoutProductApproval($params){
		$whereCheckoutId 	= array("lentera_checkout_product_id" => $params["checkout_product_id"]);
		$whereId 	= array("id" => $params["checkout_product_id"]);
		$data		= array("checkout_approved_status" 	=> $params["checkout_approved_status"],
							"checkout_approved_date"	=> $params["checkout_approved_date"],
							"checkout_approved_by"		=> $params["checkout_approved_by"],
							"updated_at"				=> $params["updated_at"],
							"updated_by"				=> $params["updated_by"]);

		$updatedStatus = $this->ud("member_checkout_product", $data, $whereId);

		if ($updatedStatus == "success"){
			switch ($params["checkout_approved_status"]){
				case MemberCheckoutProductApproval::CHECKOUT_PROCESSED:
					$dataLpoint = array("lpoint_credit_approval" => MemberLpointCreditApproval::LPOINT_CREDIT_APPROVED);
					$this->u("member_lentera_point", $dataLpoint, $whereCheckoutId);

					// send report
					$reportDescription  = "Mengupdate status checkout [".$params["checkout_code"]."] menjadi ".MemberCheckoutProductApproval::CHECKOUT_PROCESSED;
					$notifDescription	= "Pembelian Produk [".$params["checkout_code"]."] anda telah diibatalkan";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/checkout/product?type=' . strtolower(MemberCheckoutProductApproval::CHECKOUT_PROCESSED),
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
									"data"		=> "Status Checkout Produk Anggota Berhasil Diubah Menjadi : ".MemberCheckoutProductApproval::CHECKOUT_PROCESSED);
					break;
				case MemberCheckoutProductApproval::CHECKOUT_CANCEL:
					$dataLpoint = array("lpoint_credit_approval" => MemberLpointCreditApproval::LPOINT_CREDIT_CANCLED);
					$this->u("member_lentera_point", $dataLpoint, $whereCheckoutId);

					// send report
					$reportDescription  = "Mengupdate status checkout [".$params["checkout_code"]."] menjadi ".MemberCheckoutProductApproval::CHECKOUT_CANCEL;
					$notifDescription	= "Pembelian Produk [".$params["checkout_code"]."] anda telah diibatalkan";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/checkout/product?type=' . strtolower(MemberCheckoutProductApproval::CHECKOUT_CANCEL),
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
									"data"		=> "Status Checkout Produk Anggota Berhasil Diubah Menjadi : ".MemberCheckoutProductApproval::CHECKOUT_CANCEL);
					break;
				default:
					$result = array("status" 	=> "failed",
									"data"		=> "Anda Belum Merubah Status Checkout Anggota");
					break;
			}
		} else {
			$result = array("status" 	=> "success",
							"data"		=> "Terjadi kesalahan saat merubah status checkout [".$params["checkout_code"]."]. Silahkan coba lagi");
		}

		return $result;
	}
}
