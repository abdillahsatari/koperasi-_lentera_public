<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SimpananModel extends CrudModel {

	private function getAllSimpananContentMemberAnggota(){

		$where	= array("simpanan_content_type" => SimpananContentType::SIMPANAN_KEANGGOTAAN);
		$data = $this->gw("lentera_simpanan_content", $where);

		return $data;
	}

	private function getAllSimpananContentMemberFunding(){

		$where	= array("simpanan_content_type" => SimpananContentType::SIMPANAN_FUNDING);
		$data = $this->gw("lentera_simpanan_content", $where);

		return $data;

	}

	/**
	 *
	 *	Getter
	 *
	 **/

	public function getSimpananContentMember($memberId = NULL, $simpananContentType = SimpananContentType::SIMPANAN_KEANGGOTAAN){

		$result = array();

		$query	= 'SELECT * FROM simpanan_content_member SCM 
					JOIN lentera_simpanan_content LSC ON SCM.lentera_simpanan_content_id = LSC.id 
					WHERE SCM.member_id = "'. $memberId .'"
					AND LSC.simpanan_content_type = "'.$simpananContentType.'" ORDER BY lentera_simpanan_content_id';

		$dataMemberSimpananContentMember	= $this->q($query);

		foreach ($dataMemberSimpananContentMember as $contentMember){
			$querySimpananMember	=  'SELECT SM.member_id, SUM(SM.simpanan_member_ammount) total_simpanan_member
											FROM simpanan_member SM
											JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id
											Where SM.member_id = "'. $memberId.'" AND LSC.id = "'.$contentMember->lentera_simpanan_content_id.'"
											AND simpanan_is_expired IS NULL';
			$totalSimpanan	= $this->q($querySimpananMember);

			$dataSimpananMember = new stdClass();
			$dataSimpananMember->simpanan_id						= $contentMember->id;
			$dataSimpananMember->simpanan_name						= $contentMember->simpanan_name;
			$dataSimpananMember->simpanan_minimum_price				= $contentMember->simpanan_minimum_price;
			$dataSimpananMember->simpanan_maximum_price				= $contentMember->simpanan_maximum_price;
			$dataSimpananMember->simpanan_duration					= $contentMember->simpanan_duration;
			$dataSimpananMember->simpanan_balas_jasa_percentage		= $contentMember->simpanan_balas_jasa_percentage;
			$dataSimpananMember->simpanan_balas_jasa_perdays		= $contentMember->simpanan_balas_jasa_perdays;
			$dataSimpananMember->simpanan_content_type				= $contentMember->simpanan_content_type;
			$dataSimpananMember->totalSimpanan						= current($totalSimpanan)->total_simpanan_member ?: 0;

			array_push($result, $dataSimpananMember);
		}

		return $result;
	}

	public function getMemberContentFunding($id){
		$query	= 'SELECT * FROM simpanan_content_member SCM 
					JOIN lentera_simpanan_content LSC ON SCM.lentera_simpanan_content_id = LSC.id 
					WHERE SCM.member_id = "'. $id .'"
					AND LSC.simpanan_content_type = "'.SimpananContentType::SIMPANAN_FUNDING.'"';
		$result	= $this->q($query);

		return $result;
	}

	public function getAllProgramFunding(){
		return $this->getAllSimpananContentMemberFunding();
	}

	public function reportSimpananContentAnggota($id = NULL){

		$result = array();

		foreach ($this->getAllSimpananContentMemberAnggota() as $key => $value){

//			$new_str 	= str_replace(' ', '_', $value->simpanan_name);
//			$sumName	= "total_".$new_str;
			$sumName	= "total_simpanan";

			if ($id){
				$querySimpananPokok		=  'SELECT SM.member_id, SUM(SM.simpanan_member_ammount) "'.$sumName.'"
											FROM simpanan_member SM 
											JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id 
											Where LSC.simpanan_content_type = "simpanan keanggotaan" AND LSC.id = "'.$value->id.'" AND SM.member_id = "'. $id.'"';
				$totalSimpanan	= $this->q($querySimpananPokok);

			} else {
				$querySimpananPokok		=  'SELECT SM.member_id, SUM(SM.simpanan_member_ammount) "'.$sumName.'"
											FROM simpanan_member SM 
											JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id 
											Where LSC.simpanan_content_type = "simpanan keanggotaan" AND LSC.id = "'.$value->id.'"';
				$totalSimpanan	= $this->q($querySimpananPokok);
			}

			$dataReportSimpanan = new stdClass();
			$dataReportSimpanan->simpanan_id						= $value->id;
			$dataReportSimpanan->simpanan_name						= $value->simpanan_name;
			$dataReportSimpanan->simpanan_minimum_price				= $value->simpanan_minimum_price;
			$dataReportSimpanan->simpanan_maximum_price				= $value->simpanan_maximum_price;
			$dataReportSimpanan->simpanan_duration					= $value->simpanan_duration;
			$dataReportSimpanan->simpanan_balas_jasa_percentage		= $value->simpanan_balas_jasa_percentage;
			$dataReportSimpanan->simpanan_balas_jasa_perdays		= $value->simpanan_balas_jasa_perdays;
			$dataReportSimpanan->simpanan_content_type				= $value->simpanan_content_type;
			$dataReportSimpanan->totalSimpanan						= current($totalSimpanan)->total_simpanan ?: 0;

			array_push($result, $dataReportSimpanan);

		}

		return $result;
	}

	public function reportSimpananContentFunding($id = NULL){

		$result = array();

		foreach ($this->getAllSimpananContentMemberFunding() as $key => $value){

//			$new_str 	= str_replace(' ', '_', $value->simpanan_name);
//			$sumName	= "total_".$new_str;
			$sumName	= "total_simpanan";

			if ($id){
				$querySimpananPokok		=  'SELECT SM.member_id, SUM(SM.simpanan_member_ammount) "'.$sumName.'"
											FROM simpanan_member SM 
											JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id 
											Where LSC.id = "'.$value->id.'" AND SM.member_id = "'. $id.'" AND simpanan_is_expired IS NULL';
				$totalSimpanan	= $this->q($querySimpananPokok);

			} else {
				$querySimpananPokok		=  'SELECT SM.member_id, SUM(SM.simpanan_member_ammount) "'.$sumName.'"
											FROM simpanan_member SM 
											JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id 
											Where LSC.id = "'.$value->id.'" AND simpanan_is_expired IS NULL';
				$totalSimpanan	= $this->q($querySimpananPokok);
			}

			$dataReportSimpanan = new stdClass();
			$dataReportSimpanan->simpanan_id						= $value->id;
			$dataReportSimpanan->simpanan_name						= $value->simpanan_name;
			$dataReportSimpanan->simpanan_minimum_price				= $value->simpanan_minimum_price;
			$dataReportSimpanan->simpanan_maximum_price				= $value->simpanan_maximum_price;
			$dataReportSimpanan->simpanan_duration					= $value->simpanan_duration;
			$dataReportSimpanan->simpanan_balas_jasa_percentage		= $value->simpanan_balas_jasa_percentage;
			$dataReportSimpanan->simpanan_balas_jasa_perdays		= $value->simpanan_balas_jasa_perdays;
			$dataReportSimpanan->simpanan_content_type				= $value->simpanan_content_type;
			$dataReportSimpanan->totalSimpanan						= current($totalSimpanan)->total_simpanan ?: 0;

			array_push($result, $dataReportSimpanan);

		}

		return $result;
	}

	public function reportDetailSimpananMember($type = NULL){

//		$whereType 	= array("simpanan_lentera_content_name" => urldecode($type));
		$query		= 'SELECT SM.*, ME.member_full_name, ME.member_referal_code
						FROM simpanan_member SM
						JOIN member ME ON SM.member_id = ME.id
						WHERE SM.simpanan_lentera_content_name = "'.urldecode($type).'"';

		$result		= $this->q($query);
		return $result;
	}

	public function getAllSimpananAnggota($id = NULL){

		if ($id){
			$query	= 'SELECT SM.*, ME.member_full_name, ME.member_referal_code, LSC.simpanan_name
					FROM simpanan_member SM
					JOIN member ME ON SM.member_id = ME.id
					JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id
					WHERE SM.member_id = "'.$id.'"
					ORDER BY SM.created_at DESC';
		} else {
			$query	= 'SELECT SM.*, ME.member_full_name, ME.member_referal_code, LSC.simpanan_name
					FROM simpanan_member SM
					JOIN member ME ON SM.member_id = ME.id
					JOIN lentera_simpanan_content LSC ON SM.simpanan_lentera_content_id = LSC.id
					ORDER BY SM.created_at DESC';
		}

		$result = $this->q($query);

		return $result;
	}

	public function getSimpananAnggotaSummary($id = NULL){

		if ($id){
			$querySimpananAnggota 	= 'SELECT simpanan_member.id, simpanan_member.member_id, simpanan_member.simpanan_member_ammount, 
										SUM(simpanan_member.simpanan_member_ammount) total_simpanan_anggota 
										FROM simpanan_member
										JOIN lentera_simpanan_content ON simpanan_member.simpanan_lentera_content_id = lentera_simpanan_content.id
										WHERE lentera_simpanan_content.simpanan_content_type = "'.SimpananContentType::SIMPANAN_KEANGGOTAAN.'"
										AND simpanan_is_expired IS NULL
										AND member_id = "'.$id.'"';
		} else{
			$querySimpananAnggota 	= 'SELECT simpanan_member.id, simpanan_member.member_id, simpanan_member.simpanan_member_ammount, 
										SUM(simpanan_member.simpanan_member_ammount) total_simpanan_anggota 
										FROM simpanan_member
										JOIN lentera_simpanan_content ON simpanan_member.simpanan_lentera_content_id = lentera_simpanan_content.id
										WHERE lentera_simpanan_content.simpanan_content_type = "'.SimpananContentType::SIMPANAN_KEANGGOTAAN.'"
										AND simpanan_is_expired IS NULL';
		}

		return $this->q($querySimpananAnggota);
	}
	
	public function getSimpananFundingSummary($id = NULL){

		if ($id){
			$queryTotalDeposito		= 'SELECT simpanan_member.id, simpanan_member.member_id, simpanan_member.simpanan_member_ammount, 
										SUM(simpanan_member.simpanan_member_ammount) total_deposito 
										FROM simpanan_member
										JOIN lentera_simpanan_content ON simpanan_member.simpanan_lentera_content_id = lentera_simpanan_content.id
										WHERE lentera_simpanan_content.simpanan_content_type = "'.SimpananContentType::SIMPANAN_FUNDING.'"
										AND simpanan_is_expired IS NULL
										AND member_id = "'.$id.'"';
		} else{
			$queryTotalDeposito		= 'SELECT simpanan_member.id, simpanan_member.member_id, simpanan_member.simpanan_member_ammount, 
										SUM(simpanan_member.simpanan_member_ammount) total_deposito 
										FROM simpanan_member
										JOIN lentera_simpanan_content ON simpanan_member.simpanan_lentera_content_id = lentera_simpanan_content.id
										WHERE lentera_simpanan_content.simpanan_content_type = "'.SimpananContentType::SIMPANAN_FUNDING.'"
										AND simpanan_is_expired IS NULL';
		}

		return $this->q($queryTotalDeposito);
	}

	/**
	 *
	 *	Setter
	 *
	 **/

	public function setSimpananContentMemberAnggota($memberId){

		foreach ($this->getAllSimpananContentMemberAnggota() as $key => $content) {

			$data = array("lentera_simpanan_content_id" 	=> $content->id,
						  "member_id"						=> $memberId,
						  "lentera_simpanan_content_name"	=> $content->simpanan_name,
						  "created_at"						=> date('Y-m-d H:i:s'),
						  "created_by"						=> $memberId);

			$this->i("simpanan_content_member", $data);
		};
	}

	/** @noinspection PhpUndefinedVariableInspection */
	public function setSimpananMember($params){

		$whereLscId		= array("id"	=> $params["lentera_simpanan_content_id"]);
		$dataLsc		= $this->gw("lentera_simpanan_content", $whereLscId);
		$lscName		= current($dataLsc)->simpanan_name;
		$lscDuration	= current($dataLsc)->simpanan_duration;
		$lscFixedPrice	= current($dataLsc)->simpanan_fixed_price;
		$lscContentType = current($dataLsc)->simpanan_content_type;
		$lscMinimumPrice= current($dataLsc)->simpanan_minimum_price;
		$lscBalasJasaPercentage	= current($dataLsc)->simpanan_balas_jasa_percentage;
		$lscBalasJasaPerDays	= current($dataLsc)->simpanan_balas_jasa_perdays;

		$where 			= array("lentera_simpanan_content_id" 	=> $params["lentera_simpanan_content_id"],
								"member_id"						=> $params["member_id"]);
		$initializeSmc	= $this->cw("simpanan_content_member", $where);

		if ($initializeSmc == 0){
			$dataScm	= array("member_id"			 			=> $params["member_id"],
								"lentera_simpanan_content_id" 	=> $params["lentera_simpanan_content_id"],
								"lentera_simpanan_content_name"	=> $lscName,
								"created_at"					=> date('Y-m-d H:i:s'),
								"created_by"					=> $params["member_id"]);

			$this->i("simpanan_content_member", $dataScm);
		}

		$simpananTrCode = generateSimpananTrCode($params["lentera_simpanan_content_name"], $params["member_id"]);
		$whereMemberId	= array("id" => $params["member_id"]);

		switch ($params["simpanan_input_type"]){
			case "pokok":

				/*for simpanan pokok */

				$authService = authenticateMemberTrCode($params["member_id"], $params["member_transaction_code"]);

				switch($authService['status']){
					case AuthStatus::AUTHORIZED:

						//cek saldo
						$dataBalance	= current($this->FinanceModel->memberFinance($params["member_id"]))->totalWalletBalance;

						if ($dataBalance < $lscFixedPrice){
							$result = array("status" 	=> "failed",
											"data"		=> "Saldo Dompet Anda Tidak Cukup Untuk Melakukan Pembayaran");
						} else {

							// save the data
							$data	= array("member_id"						=> $params["member_id"],
											"simpanan_lentera_content_id"	=> $params["lentera_simpanan_content_id"],
											"simpanan_member_ammount"		=> $lscFixedPrice,
											"simpanan_lentera_content_name"	=> $lscName,
											"simpanan_transaction_code"		=> $simpananTrCode,
											"created_at"					=> date('Y-m-d H:i:s'),
											"created_by"					=> $params["member_id"]);

							$statusAddNewSimpanan = $this->is("simpanan_member", $data);

							if ($statusAddNewSimpanan == "success") {

								//send report
								$insertedId	= $this->db->insert_id();
								$data 	= array("transaction_id"		=> $insertedId,
												"transaction_input_type"=> TransactionInputType::CREDIT,
												"transaction_type"		=> TransactionType::SIMPANAN);

								$this->ActivityLog->transactionLog($data);

								$reportAmmount = number_format(intval($lscFixedPrice),0,'.','.');
								$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." [".$simpananTrCode."] Sebesar Rp.".$reportAmmount;
								$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." [".$simpananTrCode."] Sebesar Rp.".$reportAmmount;

								$data	= array("member_id"				=> $params["member_id"],
												"user_type"				=> CredentialType::MEMBER,
												"receiver"				=> CredentialType::ADMIN,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'admin/simpanan/laporan/index',
												"created_at"			=> date('Y-m-d H:i:s'));

								$this->ActivityLog->actionLog($data);

								// set simpo status in member table
								$dataSimpoStatus= array("member_is_simpo"		=> TRUE,
														"updated_at"			=> date('Y-m-d H:i:s'),
														"updated_by"			=> $params["member_id"]);

								$MemberSimpoUpdateStatus = $this->ud("member", $dataSimpoStatus, $whereMemberId);

								if ($MemberSimpoUpdateStatus == "success"){

									// check activated member status
									$whereKyc	= array("id" => $params["member_id"],"member_is_kyc" => true);
									$whereSimpo	= array("id" => $params["member_id"],"member_is_simpo" => true);
									$whereSimwa	= array("id" => $params["member_id"],"member_is_simwa" => true);

									$memberKycStatus 	= $this->cw("member", $whereKyc);
									$memberSimpoStatus 	= $this->cw("member", $whereSimpo);
									$memberSimwaStatus 	= $this->cw("member", $whereSimwa);

									if ($memberKycStatus > 0 && $memberSimwaStatus > 0 && $memberSimpoStatus > 0) {
										// set activated member status
										$dataActivatedStatus = array("member_is_activated"	=> TRUE,
																	"updated_at"			=> date('Y-m-d H:i:s'),
																	"updated_by"			=> $params["member_id"]);

										$this->u("member", $dataActivatedStatus, $whereMemberId);

										$this->session->set_userdata("member_is_activated", 1);
									}

									$result = array("status" 	=> "success",
													"data"		=> "Pembayaran Simpanan Pokok Telah Berhasil");
								} else {
									$result = array("status" 	=> "failed",
													"data"		=> "Pembayaran simpanan pokok telah berhasil. Namun terjadi kesalahan saat melakukan perubahan status");
								}

							} else {
								$result = array("status" 	=> "failed",
												"data"		=> "Terjadi kesalahan saat melakukan pembayaran simpanan pokok");
							}
						}

						break;
					case AuthStatus::UNAUTHORIZED:
						$result = array("status" 	=> "failed",
										"data"		=> "Mohon periksa kembali kode transaksi anda");
						break;
					default:
						$result = array("status" 	=> "failed",
										"data"		=> "Mohon periksa kembali kode transaksi anda");
				}

				return $result;
				break;
			case "month":
				/*for simpanan wajib */

				$authService = authenticateMemberTrCode($params["member_id"], $params["member_transaction_code"]);

				switch($authService['status']){
					case AuthStatus::AUTHORIZED:
						//cek saldo
						$requestedMonth 	= $params["simpanan_member_month"];
						$totalPayment		= $lscFixedPrice * $requestedMonth;
						$dataBalance		= current($this->FinanceModel->memberFinance($params["member_id"]))->totalWalletBalance;

						if ($dataBalance < $totalPayment){
							$result = array("status" 	=> "failed",
											"data"		=> "Saldo Dompet Anda Tidak Cukup Untuk Melakukan Pembayaran");
						} else {

							$where 			= array("simpanan_lentera_content_id" 	=> $params["lentera_simpanan_content_id"],
													"simpanan_lentera_content_name"	=> $lscName,
													"member_id"						=> $params["member_id"]);
							$initializeSM	= $this->cw("simpanan_member", $where);

							if ($initializeSM <= 0){
								// for the first time member pay simpanan wajib

								$getMemberRegisterAt	= 'SELECT created_at FROM member WHERE id = "'.$params["member_id"].'"';
								$memberJoinDate			= current($this->q($getMemberRegisterAt))->created_at;
								$tgl 					= date("Y-m", strtotime($memberJoinDate)) . "-01";

								// loop for month ammount
								for ($i=0; $i < $requestedMonth; $i++ ){

									//save the data
									$simpanan_wajib_date = date("Y-m-d", strtotime("+". $i . " month", strtotime($tgl)));
									$data	= array("member_id"						=> $params["member_id"],
													"simpanan_lentera_content_id"	=> $params["lentera_simpanan_content_id"],
													"simpanan_member_ammount"		=> $lscFixedPrice,
													"simpanan_lentera_content_name"	=> $lscName,
													"simpanan_transaction_code"		=> $simpananTrCode,
													"simpanan_wajib_date"			=> $simpanan_wajib_date,
													"created_at"					=> date('Y-m-d H:i:s'),
													"created_by"					=> $params["member_id"]);

									$this->i("simpanan_member", $data);

									//send report
									$insertedId	= $this->db->insert_id();
									$data 		= array("transaction_id"		=> $insertedId,
														"transaction_input_type"=> TransactionInputType::CREDIT,
														"transaction_type"		=> TransactionType::SIMPANAN);

									$this->ActivityLog->transactionLog($data);

									$reportAmmount 	= number_format(intval($lscFixedPrice),0,'.','.');
									$getMonth		= date("m", strtotime($simpanan_wajib_date));
									$getYear		= date("Y", strtotime($simpanan_wajib_date));
									$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." untuk bulan ".$getMonth." - ".$getYear." Sebesar Rp.".$reportAmmount;
									$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." untuk bulan ".$getMonth." - ".$getYear." Sebesar Rp.".$reportAmmount;

									$data	= array("member_id"				=> $params["member_id"],
													"user_type"				=> CredentialType::MEMBER,
													"receiver"				=> CredentialType::ADMIN,
													"report_description"	=> $reportDescription,
													"notif_description"		=> $notifDescription,
													"reference_link"		=> 'admin/simpanan/laporan/index',
													"created_at"			=> date('Y-m-d H:i:s'));
									$this->ActivityLog->actionLog($data);
								}

								if ($initializeSM >= 0){
									// set simwa status in member table
									$dataSimwaStatus= array("member_is_simwa"		=> TRUE,
															"updated_at"			=> date('Y-m-d H:i:s'),
															"updated_by"			=> $params["member_id"]);

									$memberSimwaUpdateStatus = $this->ud("member", $dataSimwaStatus, $whereMemberId);
									if ($memberSimwaUpdateStatus == "success"){

										// check activated member status
										$whereKyc	= array("id" => $params["member_id"],"member_is_kyc" => true);
										$whereSimpo	= array("id" => $params["member_id"],"member_is_simpo" => true);
										$whereSimwa	= array("id" => $params["member_id"],"member_is_simwa" => true);

										$memberKycStatus 	= $this->cw("member", $whereKyc);
										$memberSimpoStatus 	= $this->cw("member", $whereSimpo);
										$memberSimwaStatus 	= $this->cw("member", $whereSimwa);

										if ($memberKycStatus > 0 && $memberSimwaStatus > 0 && $memberSimpoStatus > 0) {
											// set activated member status
											$dataActivatedStatus = array("member_is_activated"	=> TRUE,
												"updated_at"			=> date('Y-m-d H:i:s'),
												"updated_by"			=> $params["member_id"]);

											$this->u("member", $dataActivatedStatus, $whereMemberId);

											$this->session->set_userdata("member_is_activated", 1);
										}

										$result = array("status" 	=> "success",
														"data"		=> "Pembayaran simpanan wajib telah berhasil. Terima kasih telah melakukan pembayaran simpanan wajib");
									} else {
										$result = array("status" 	=> "failed",
														"data"		=> "Pembayaran simpanan wajib telah berhasil. Namun terjadi kesalahan saat melakukan perubahan status");
									}

								} else {
									$result = array("status" 	=> "Failed",
													"date"		=> "Terjadi kesalahan saat melakukan pembayaran simpanan wajib. Mohon coba beberapa saat lagi");
								}

							} else {

								// get the last time member pay simpanan wajib
								$getMemberLAstPayment	= 'SELECT max(simpanan_wajib_date) as last_payment FROM simpanan_member WHERE member_id = "'.$params["member_id"].'"';
								$memberLastPayment		= current($this->q($getMemberLAstPayment))->last_payment;
								$dateTime 				= strtotime($memberLastPayment);

								for ($i = 1; $i <= $requestedMonth; $i++) {
									//save the data
									$simpanan_wajib_date = date("Y-m-d", strtotime("+" . $i . " month", $dateTime));
									$data	= array("member_id"						=> $params["member_id"],
													"simpanan_lentera_content_id"	=> $params["lentera_simpanan_content_id"],
													"simpanan_member_ammount"		=> $lscFixedPrice,
													"simpanan_lentera_content_name"	=> $lscName,
													"simpanan_transaction_code"		=> $simpananTrCode,
													"simpanan_wajib_date"			=> $simpanan_wajib_date,
													"created_at"					=> date('Y-m-d H:i:s'),
													"created_by"					=> $params["member_id"]);

									$statusAddNewSimpanan = $this->is("simpanan_member", $data);

									if ($statusAddNewSimpanan == "success"){

										//send report
										$insertedId	= $this->db->insert_id();
										$data 	= array("transaction_id"		=> $insertedId,
														"transaction_input_type"=> TransactionInputType::CREDIT,
														"transaction_type"		=> TransactionType::SIMPANAN);

										$this->ActivityLog->transactionLog($data);

										$reportAmmount 	= number_format(intval($lscFixedPrice),0,'.','.');
										$getMonth		= date("m", strtotime($simpanan_wajib_date));
										$getYear		= date("Y", strtotime($simpanan_wajib_date));
										$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." untuk bulan ".$getMonth." ".$getYear." Sebesar Rp.".$reportAmmount;
										$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." untuk bulan ".$getMonth." ".getYear." Sebesar Rp.".$reportAmmount;

										$data	= array("member_id"				=> $params["member_id"],
														"user_type"				=> CredentialType::MEMBER,
														"receiver"				=> CredentialType::ADMIN,
														"report_description"	=> $reportDescription,
														"notif_description"		=> $notifDescription,
														"reference_link"		=> 'admin/simpanan/laporan/index',
														"created_at"			=> date('Y-m-d H:i:s'));
										$this->ActivityLog->actionLog($data);

										$result = array("status" 	=> "success",
														"data"		=> "Pembayaran simpanan wajib telah berhasil. Terima kasih telah melakukan pembayaran simpanan wajib");
									} else {
										$result = array("status" 	=> "Failed",
														"date"		=> "Terjadi kesalahan saat melakukan pembayaran simpanan wajib. Mohon coba beberapa saat lagi");
									}
								}
							}
						}
						break;
					case AuthStatus::UNAUTHORIZED:
						$result = array("status" 	=> "failed",
										"data"		=> "Mohon periksa kembali kode transaksi anda");
						break;
					default:
						$result = array("status" 	=> "failed",
										"data"		=> "Mohon periksa kembali kode transaksi anda");
				}

				return $result;
				break;
			case "ammount":

				/*for simpanan sukarela and all simpanan funding type */

				$authService = authenticateMemberTrCode($params["member_id"], $params["member_transaction_code"]);

				switch($authService['status']){
					case AuthStatus::AUTHORIZED:

						//cek saldo
						$dataBalance	= current($this->FinanceModel->memberFinance($params["member_id"]))->totalWalletBalance;

						if (intval($params["simpanan_member_ammount"]) < $lscMinimumPrice){

							$result = array("status" 	=> "failed",
											"data"		=> "Nominal simpanan yang anda inginkan belum mencukupi minimal pembayaran simpanan dalam program yang anda pilih.");

						} elseif ($dataBalance < intval($params["simpanan_member_ammount"])){

							$result = array("status" 	=> "failed",
											"data"		=> "Saldo Dompet Anda Tidak Cukup Untuk Melakukan Pembayaran");

						} else {

							// set expired date & imbal jasa
							if ($lscContentType == SimpananContentType::SIMPANAN_FUNDING){
								$getDateNow = date('Y-m-d H:i:s');
								$simpananActiveDate = NULL;
								$simpanan_expired_date 	= date("Y-m-d", strtotime("+". $lscDuration . " month", strtotime($getDateNow)));
								$simpanan_active_date 	= date("Y-m-d", strtotime("+" . $lscBalasJasaPerDays . " month", strtotime($getDateNow)));
								$sijIndex = $lscDuration / $lscBalasJasaPerDays;

								for ($i = 1; $i <= $sijIndex; $i++){

									$ijActiveDate = $simpananActiveDate ?: $simpanan_active_date;
									$sijAmmount	= $params["simpanan_member_ammount"] * ($lscBalasJasaPercentage/100);
									$sijTrCode	= "SIJ-".generatePaymentTransactionCode($params["member_id"]);
									$dataSij	= array("member_id"				=> $params["member_id"],
														"simpanan_content_id"	=> $params["lentera_simpanan_content_id"],
														"simpanan_content_name"	=> $lscName,
														"sij_ammount"			=> $sijAmmount,
														"sij_transaction_code"	=> $sijTrCode,
														"sij_active_date"		=> $ijActiveDate,
														"created_at"			=> $getDateNow);

									$insertedId = $this->CrudModel->i2("simpanan_imbal_jasa", $dataSij);
									$whereId = array("id" => $insertedId);
									$iterateActiveDate = current($this->CrudModel->gw("simpanan_imbal_jasa", $whereId))->sij_active_date;
									$simpananActiveDate = date("Y-m-d", strtotime("+" . $lscBalasJasaPerDays . " month", strtotime($iterateActiveDate)));

								}

							} else {
								$simpanan_expired_date = NULL;
							}

							// save the data
							$data	= array("member_id"						=> $params["member_id"],
											"simpanan_lentera_content_id"	=> $params["lentera_simpanan_content_id"],
											"simpanan_member_ammount"		=> $params["simpanan_member_ammount"],
											"simpanan_lentera_content_name"	=> $lscName,
											"simpanan_transaction_code"		=> $simpananTrCode,
											"simpanan_expired_date"			=> $simpanan_expired_date,
											"created_at"					=> date('Y-m-d H:i:s'),
											"created_by"					=> $params["member_id"]);

							$statusAddNewSimpanan = $this->is("simpanan_member", $data);

							if ($statusAddNewSimpanan == "success") {

								//send report
								$insertedId	= $this->db->insert_id();
								$data 	= array("transaction_id"		=> $insertedId,
												"transaction_input_type"=> TransactionInputType::CREDIT,
												"transaction_type"		=> TransactionType::SIMPANAN);

								$this->ActivityLog->transactionLog($data);

								$reportAmmount 	= number_format(intval($params["simpanan_member_ammount"]),0,'.','.');
								$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." Sebesar Rp.".$reportAmmount;
								$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar ".$lscName." Sebesar Rp.".$reportAmmount;

								$data	= array("member_id"				=> $params["member_id"],
												"user_type"				=> CredentialType::MEMBER,
												"receiver"				=> CredentialType::ADMIN,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'admin/simpanan/laporan/index',
												"created_at"			=> date('Y-m-d H:i:s'));
								$this->ActivityLog->actionLog($data);

								$result = array("status" 	=> "success",
												"data"		=> "Pembayaran ".$lscName." Telah Berhasil");

							} else {
								$result = array("status" 	=> "failed",
												"data"		=> "Terjadi Kesalahan Saat Melakukan Pembayaran ".$lscName."");
							}
						}

						break;
					case AuthStatus::UNAUTHORIZED:
						$result = array("status" 	=> "failed",
										"data"		=> "Mohon periksa kembali kode transaksi anda");
						break;
					default:
						$result = array("status" 	=> "failed",
										"data"		=> "Mohon periksa kembali kode transaksi anda");
				}

				return $result;
				break;
		}

	}

}
