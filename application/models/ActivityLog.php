<?php

class ActivityLog extends CrudModel{

	private function getIpAddress(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //ip from client network
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) // ip from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	/**
	 *
	 *	Getter
	 *
	 **/

	public function getAdminNotification(){
		$query			= 'SELECT * FROM action_log WHERE receiver = "'.CredentialType::ADMIN.'" AND notif_description IS NOT NULL AND is_opened IS NULL ORDER BY created_at DESC';
		$dataNotif		= $this->q($query);
		$dataCountNotif	= count($dataNotif);

		$result = array();
		$dataResult	= new stdClass();
		$dataResult->dataNotif 		= $dataNotif;
		$dataResult->dataCountNotif = $dataCountNotif ?: NULL;

		array_push($result, $dataResult);

		return $result;
	}

	public function getMemberNotification(){
		$query			= 'SELECT * FROM action_log WHERE receiver = "'.CredentialType::MEMBER.'" 
							AND notif_description IS NOT NULL AND is_opened IS NULL 
							AND member_id = "'.$this->session->userdata("member_id").'" ORDER BY created_at DESC';
		$dataNotif		= $this->q($query);
		$dataCountNotif	= count($dataNotif);

		$result = array();
		$dataResult	= new stdClass();
		$dataResult->dataNotif 		= $dataNotif;
		$dataResult->dataCountNotif = $dataCountNotif ?: NULL;

		array_push($result, $dataResult);

		return $result;
	}


	/**
	 *
	 *	Setter
	 *
	 **/

	function authLog($id = NULL, $suffix = NULL){

		$data	= array("user_id"		=> $id,
						"ip_address"	=> $this->getIpAddress(),
						"user_agents"	=> $_SERVER['HTTP_USER_AGENT'],
						"suffix"		=> $suffix,
						"created_at"	=> date('Y-m-d H:i:s'));

		$this->i("auth_log", $data);
	}

	function actionLog($params){

		/* wheter you need the status return*/
		return $this->is("action_log", $params);

	}

	function transactionLog($params){
		$transactionId			= $params["transaction_id"];
		$transactionInputType	= $params["transaction_input_type"];
		$transactionType		= $params["transaction_type"];
		$result					= "";

		switch ($transactionInputType){
			case TransactionInputType::DEBIT:
				switch ($transactionType){
					case TransactionType::DEPOSIT:

						$query 			= 'SELECT MD.* , LBA.nama_bank, LBA.nomor_rekening 
											FROM member_deposit MD 
											JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id =  LBA.id
											WHERE MD.id = "'.$transactionId.'"';

						$dataDeposit	= current($this->q($query));

						$dataDescription= "Deposit [".$dataDeposit->deposit_transaction_code."] ke ".strtoupper($dataDeposit->nama_bank)." - ". $dataDeposit->nomor_rekening;

						$data			= array("admin_id" 			=> $this->session->userdata("admin_id"),
												"member_id"			=> $dataDeposit->deposit_member_id,
												"transaction_id"	=> $dataDeposit->id,
												"transaction_code"	=> $dataDeposit->deposit_transaction_code,
												"debit"				=> $dataDeposit->deposit_ammount,
												"balance"			=> current($this->FinanceModel->memberFinance($dataDeposit->deposit_member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::ADMIN,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case TransactionType::SIMPANAN:

						$query 			= 'SELECT SM.*, ME.member_referal_code FROM simpanan_member SM JOIN member ME ON SM.member_id = ME.id WHERE SM.id = "'.$transactionId.'"';

						$dataSimpanan	= current($this->q($query));

						$reportAmmount 	= number_format(intval($dataSimpanan->simpanan_member_ammount),0,'.','.');
						$dataDescription= "Masa Kontrak ".$dataSimpanan->simpanan_lentera_content_name." [".$dataSimpanan->simpanan_transaction_code."] Telah Berakhir. Simpanan sebesar Rp. ".$reportAmmount." Dikembalikan Ke saldo Dompet";

						$data	= array("member_id" 		=> $dataSimpanan->member_id,
										"transaction_id"	=> $dataSimpanan->id,
										"transaction_code"	=> $dataSimpanan->simpanan_transaction_code,
										"debit"				=> $dataSimpanan->simpanan_member_ammount,
										"balance"			=> current($this->FinanceModel->memberFinance($dataSimpanan->member_id))->totalWalletBalance,
										"description"		=> $dataDescription,
										"transaction_type"	=> $transactionType,
										"suffix"			=> CredentialType::AUTOMATIC_SYSTEM,
										"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case TransactionType::SIMPANAN_IMBAL_JASA:

						$query 			= 'SELECT SIJ.*, ME.member_referal_code FROM simpanan_imbal_jasa SIJ JOIN member ME ON SIJ.member_id = ME.id WHERE SIJ.id = "'.$transactionId.'"';

						$dataSijMember	= current($this->q($query));

						$dataDescription  	= "Mendapat Imbal Jasa Dari ".$dataSijMember->simpanan_content_name." [".$dataSijMember->sij_transaction_code."]";

						$data	= array("member_id" 		=> $dataSijMember->member_id,
										"transaction_id"	=> $dataSijMember->id,
										"transaction_code"	=> $dataSijMember->sij_transaction_code,
										"debit"				=> $dataSijMember->sij_ammount,
										"balance"			=> current($this->FinanceModel->memberFinance($dataSijMember->member_id))->totalWalletBalance,
										"description"		=> $dataDescription,
										"transaction_type"	=> $transactionType,
										"suffix"			=> CredentialType::AUTOMATIC_SYSTEM,
										"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case TransactionType::TABUNGAN_DEBIT:
						$whereId		= array("id"=> $transactionId);
						$dataTabungan	= current($this->gw("tabungan_member", $whereId));
						$dataDescription= "Menabung [".$dataTabungan->tabungan_tr_code."] sebesar ".$dataTabungan->tabungan_ammount;
						$data			= array("admin_id" 			=> $this->session->userdata("admin_id"),
							"member_id"			=> $dataTabungan->member_id,
							"transaction_id"	=> $dataTabungan->id,
							"transaction_code"	=> $dataTabungan->tabungan_tr_code,
							"debit"				=> $dataTabungan->tabungan_ammount,
							"balance"			=> current($this->FinanceModel->memberFinance($dataTabungan->member_id))->totalWalletBalance,
							"description"		=> $dataDescription,
							"transaction_type"	=> $transactionType,
							"suffix"			=> CredentialType::ADMIN,
							"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;
						break;
					case TransactionType::PINJAMAN_DEBIT:
						$whereId		= array("id"=> $transactionId);
						$dataPinjaman	= current($this->gw("member_pinjaman", $whereId));
						$dataDescription= "Mendapat Pinjaman [".$dataPinjaman->pinjaman_transaction_code."] sebesar ".$dataPinjaman->pinjaman_member_ammount;
						$data			= array("admin_id" 			=> $this->session->userdata("admin_id"),
												"member_id"			=> $dataPinjaman->member_id,
												"transaction_id"	=> $dataPinjaman->id,
												"transaction_code"	=> $dataPinjaman->pinjaman_transaction_code,
												"debit"				=> $dataPinjaman->pinjaman_member_ammount,
												"balance"			=> current($this->FinanceModel->memberFinance($dataPinjaman->member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::ADMIN,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;
						break;
					default:
						$result = "failed";
				}

				break;
			case TransactionInputType::CREDIT:
				switch ($transactionType){
					case TransactionType::WITHDRAWAL:

						$query 			= 'SELECT WM.* , MBA.bank_account_name, MBA.bank_account_number 
											FROM withdrawal_member WM 
											JOIN member_bank_account MBA ON WM.wd_member_bank_id = MBA.id
											WHERE WM.id = "'.$transactionId.'"';

						$dataWd			= current($this->q($query));

						$dataDescription= "Withdraw [".$dataWd->wd_transaction_code."] ke ".strtoupper($dataWd->bank_account_name)." - ". $dataWd->bank_account_number;

						$data			= array("admin_id" 			=> $this->session->userdata("admin_id"),
												"member_id"			=> $dataWd->wd_member_id,
												"transaction_id"	=> $dataWd->id,
												"transaction_code"	=> $dataWd->wd_transaction_code,
												"credit"			=> $dataWd->wd_ammount,
												"balance"			=> current($this->FinanceModel->memberFinance($dataWd->wd_member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::ADMIN,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case  TransactionType::SIMPANAN:

						$query 			= 'SELECT SM.* FROM simpanan_member SM WHERE SM.id = "'.$transactionId.'"';

						$dataSimpanan	= current($this->q($query));

						$dataDescription= "Membayar ".$dataSimpanan->simpanan_lentera_content_name;

						$data			= array("member_id" 		=> $this->session->userdata("member_id"),
												"transaction_id"	=> $dataSimpanan->id,
												"transaction_code"	=> $dataSimpanan->simpanan_transaction_code,
												"credit"			=> $dataSimpanan->simpanan_member_ammount,
												"balance"			=> current($this->FinanceModel->memberFinance($dataSimpanan->member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::MEMBER,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case TransactionType::TABUNGAN_CREDIT:

						$whereId		= array("id"=> $transactionId);
						$dataTabunganTf	= current($this->gw("tabungan_member_transfer", $whereId));
						$dataDescription= "Mentransfer Tabungan [".$dataTabunganTf->tabungan_tf_tr_code."] sebesar ".$dataTabunganTf->tabungan_tf_ammount." Ke - ".$dataTabunganTf->tabungan_tf_type;
						$data			= array("admin_id" 			=> $this->session->userdata("admin_id"),
												"member_id"			=> $dataTabunganTf->member_id,
												"transaction_id"	=> $dataTabunganTf->id,
												"transaction_code"	=> $dataTabunganTf->tabungan_tf_tr_code,
												"credit"			=> $dataTabunganTf->tabungan_tf_ammount,
												"balance"			=> current($this->FinanceModel->memberFinance($dataTabunganTf->member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::ADMIN,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case TransactionType::PINJAMAN_CREDIT:
						$whereId		= array("id" => $transactionId);
						$dataPinjaman 	= current($this->gw("member_pinjaman_detail", $whereId));

						$reportAmmount 	= number_format(intval($dataPinjaman->pinjaman_bunga_ammount),0,'.','.');
						$dataDescription= "Membayar Pinjaman Sebesar Rp.".$reportAmmount;

						$data			= array("member_id" 		=> $this->session->userdata("member_id"),
												"transaction_id"	=> $dataPinjaman->id,
												"transaction_code"	=> $dataPinjaman->pinjaman_detail_payment_code,
												"credit"			=> $dataPinjaman->pinjaman_bunga_ammount,
												"balance"			=> current($this->FinanceModel->memberFinance($dataPinjaman->member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::MEMBER,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;

						break;
					case TransactionType::LPOINT_CREDIT:
						$whereId				= array("id" => $transactionId);
						$dataCheckoutProduct 	= current($this->gw("member_checkout_product", $whereId));
						$totalAmmount			= $dataCheckoutProduct->olshop_product_price * $dataCheckoutProduct->checkout_quantity;

						$reportAmmount 	= number_format(intval($totalAmmount),0,'.','.');
						$dataDescription= "Membeli Produk di Olshop sejumlah Rp.".$reportAmmount;

						$data			= array("member_id" 		=> $this->session->userdata("member_id"),
												"transaction_id"	=> $dataCheckoutProduct->id,
												"transaction_code"	=> $dataCheckoutProduct->checkout_code,
												"credit"			=> intval($totalAmmount),
												"balance"			=> current($this->FinanceModel->memberFinance($dataCheckoutProduct->member_id))->totalWalletBalance,
												"description"		=> $dataDescription,
												"transaction_type"	=> $transactionType,
												"suffix"			=> CredentialType::MEMBER,
												"created_at"		=> date('Y-m-d H:i:s'));

						$insertStatus	= $this->is("transaction_log", $data);

						$result = $insertStatus;
						break;
					default:

						$result = "failed";
				}

				break;
			default:
				$result = "failed";

		}

		return $result;
	}

}
