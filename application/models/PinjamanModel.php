<?php

class PinjamanModel extends CrudModel {

	private function pinjamanContent($pinjamanContentId){
		$whereId 		= array("id" => $pinjamanContentId);
		$getPinjaman	= $this->gw("lentera_pinjaman_content", $whereId);

		return $getPinjaman;
	}

	private function pinjamanContentDetail($pinjamanContentId){
		$whereId 	= array("pinjaman_content_id" => $pinjamanContentId);
		$getDetail	= $this->gw("lentera_pinjaman_detail", $whereId);

		return $getDetail;
	}

	/**
	 *
	 *	Getter
	 *
	 **/

	public function getPinjamanContentDetail($pinjamanContentId){
		$pinjamanContent		= $this->pinjamanContent($pinjamanContentId);
		$pinjamanContentDetail	= $this->pinjamanContentDetail($pinjamanContentId);

		$pinjaman	= current($pinjamanContent);

		$result 	= array();
		$dataResult = new stdClass();
		$dataResult->pinjaman_id				= $pinjaman->id;
		$dataResult->pinjaman_name				= $pinjaman->pinjaman_name;
		$dataResult->pinjaman_content_status	= $pinjaman->pinjaman_content_status;
		$dataResult->pinjaman_content_tenor_type= $pinjaman->pinjaman_content_tenor_type;
		$dataResult->pinjaman_setting			= $pinjamanContentDetail;

		array_push($result, $dataResult);

		return $result;
	}

	public function getMemberPinjamanSummary($memberId = NULL){

		$result = array();

		if ($memberId){
			$queryAllPinjamanMember 	= 'SELECT member_pinjaman.id, member_pinjaman.member_id, member_pinjaman.pinjaman_member_ammount, member_pinjaman.approved_status, 
										SUM(member_pinjaman.pinjaman_member_ammount) total_all_pinjaman 
										FROM member_pinjaman 
										WHERE approved_status = "'.MemberPinjamanApproval::PINJAMAN_PROCESSED.'"
										AND member_id = "'.$memberId.'"';

			$queryPaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
										SUM(MPD.pinjaman_bunga_ammount) total_paid_pinjaman 
										FROM member_pinjaman_detail MPD
										WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_PAID.'"
										AND member_id = "'.$memberId.'"';

			$queryUnpaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
										SUM(MPD.pinjaman_bunga_ammount) total_unpaid_pinjaman 
										FROM member_pinjaman_detail MPD
										WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_UNPAID.'"
										AND member_id = "'.$memberId.'"';
		} else {
			$queryAllPinjamanMember 	= 'SELECT member_pinjaman.id, member_pinjaman.member_id, member_pinjaman.pinjaman_member_ammount, member_pinjaman.approved_status, 
										SUM(member_pinjaman.pinjaman_member_ammount) total_all_pinjaman 
										FROM member_pinjaman 
										WHERE approved_status = "'.MemberPinjamanApproval::PINJAMAN_PROCESSED.'"';

			$queryPaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
										SUM(MPD.pinjaman_bunga_ammount) total_paid_pinjaman 
										FROM member_pinjaman_detail MPD
										WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_PAID.'"';

			$queryUnpaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
										SUM(MPD.pinjaman_bunga_ammount) total_unpaid_pinjaman 
										FROM member_pinjaman_detail MPD
										WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_UNPAID.'"';
		}

		$allPinjaman		= $this->q($queryAllPinjamanMember);
		$paidPinjaman		= $this->q($queryPaidPinjamanMember);
		$unpaidPinjaman		= $this->q($queryUnpaidPinjamanMember);

		$totalPinjaman			= current($allPinjaman)->total_all_pinjaman ?: 0;
		$totalPaidPinjaman		= current($paidPinjaman)->total_paid_pinjaman ?: 0;
		$totalUnpaidPinjaman	= current($unpaidPinjaman)->total_unpaid_pinjaman ?: 0;

		/**
		 * Wrapping all components
		 * */

		$dataPinjaman 	= new stdClass();
		$dataPinjaman->countAllPinjaman		= $totalPinjaman;
		$dataPinjaman->countPaidPinjaman		= $totalPaidPinjaman;
		$dataPinjaman->countUnpaidPinjaman	= $totalUnpaidPinjaman;

		array_push($result, $dataPinjaman);

		return $result;
	}

	public function getMemberPinjamanContent($memberId){

		$result = array();

		$query	= 'SELECT *, MPC.id as member_pinjaman_content_id FROM member_pinjaman_content MPC
					JOIN lentera_pinjaman_content LPC ON MPC.pinjaman_content_id = LPC.id
					WHERE MPC.member_id = "'.$memberId.'"';

		$dataMemberPinjamanContent	= $this->q($query);

		foreach ($dataMemberPinjamanContent as $memberPinjamanContent) {
			$queryMemberPinjaman = 'SELECT MP.member_id, SUM(MP.pinjaman_member_ammount) total_pinjaman_member
									FROM member_pinjaman MP
									JOIN lentera_pinjaman_content LPC ON MP.pinjaman_content_id = LPC.id
									WHERE MP.member_id = "'.$memberId.'" AND MP.approved_status = "'.MemberPinjamanApproval::PINJAMAN_PROCESSED.'" AND LPC.id = "'.$memberPinjamanContent->pinjaman_content_id.'"';
			$totalPinjaman	= $this->q($queryMemberPinjaman);

			$dataPinjamanMember	= new stdClass();
			$dataPinjamanMember->member_pinjaman_content_id		= $memberPinjamanContent->member_pinjaman_content_id;
			$dataPinjamanMember->member_pinjaman_content_name	= $memberPinjamanContent->pinjaman_name;
			$dataPinjamanMember->member_pinjaman_total_ammount	= current($totalPinjaman)->total_pinjaman_member ?: 0;

			array_push($result, $dataPinjamanMember);
		}

		return $result;
	}

	public function getMemberPinjamanList($memberId = NULL, $pinjamanStatus = NULL){

		/**
		 *
		 *
		 * 	$pinjamanStatus = MemberPinjamanApproval::PINJAMAN_NEW || MemberPinjamanApproval::PINJAMAN_PROCESSED: for member_pinjaman
		 *  $pinjamanStatus = MemberPinjamanStatus::PINJAMAN_PAID || MemberPinjamanStatus::PINJAMAN_UNPAID for member_pinjaman_Detail
		 *
		 *
		**/

		if ($memberId){
			switch ($pinjamanStatus){
				case MemberPinjamanStatus::PINJAMAN_PAID || MemberPinjamanStatus::PINJAMAN_UNPAID:
					$queryWithStatus 	= 'SELECT MP.*, MPD.*, LPC.pinjaman_name, LPC.pinjaman_content_tenor_type, 
       												MP.id as member_pinjaman_id, MPD.id as member_pinjaman_detail_id, LPC.id as lentera_pinjaman_content_id
											FROM member_pinjaman_detail MPD
											JOIN member_pinjaman MP ON MPD.pinjaman_id = MP.id
											JOIN lentera_pinjaman_content LPC ON MP.pinjaman_content_id = LPC.id 
											WHERE pinjaman_payment_status = "'.$pinjamanStatus.'"
											AND MP.member_id = "'.$memberId.'"';

					$result = $this->q($queryWithStatus);
					break;
				default:
					$queryAll 	= 'SELECT *, MP.id as member_pinjaman_id, ME.member_referal_code, LPC.pinjaman_content_tenor_type FROM member_pinjaman MP 
									JOIN member ME ON MP.member_id = ME.id
									JOIN lentera_pinjaman_content LPC ON MP.pinjaman_content_id = LPC.id
									AND MP.member_id = "'.$memberId.'"';

					$result	= $this->q($queryAll);
					break;
			}

		} else {
			switch ($pinjamanStatus){
				case MemberPinjamanApproval::PINJAMAN_NEW:
				case MemberPinjamanApproval::PINJAMAN_PROCESSED:
					$query 	= 'SELECT *, MP.id as member_pinjaman_id FROM member_pinjaman MP 
								JOIN member ME ON MP.member_id = ME.id
								JOIN lentera_pinjaman_content LPC ON MP.pinjaman_content_id = LPC.id
								WHERE MP.approved_status = "'.$pinjamanStatus.'"';

					$result	= $this->q($query);
					break;
				case MemberPinjamanStatus::PINJAMAN_PAID:
					$queryWithStatus 	= 'SELECT *, MP.id as member_pinjaman_id FROM member_pinjaman_detail MPD
											JOIN member_pinjaman MP ON MPD.pinjaman_id = MP.id
    										JOIN member ME ON MPD.member_id = ME.id
											JOIN lentera_pinjaman_content LPC ON MP.pinjaman_content_id = LPC.id 
											WHERE pinjaman_payment_status = "'.$pinjamanStatus.'"';

					$result = $this->q($queryWithStatus);
					break;
				default:
					$query 	= 'SELECT *, MP.id as member_pinjaman_id FROM member_pinjaman MP 
								JOIN member ME ON MP.member_id = ME.id
								JOIN lentera_pinjaman_content LPC ON MP.pinjaman_content_id = LPC.id';

					$result	= $this->q($query);
					break;
			}
		}

		return $result;
	}

	public function getMemberPinjamanDetailSummary($pinjamanId = NULL, $memberId = NULL){

		$result = array();
		
		if ($pinjamanId){

			$queryPaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
									SUM(MPD.pinjaman_bunga_ammount) total_paid_pinjaman 
									FROM member_pinjaman_detail MPD
									WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_PAID.'"
									AND MPD.pinjaman_id = "'.$pinjamanId.'"';

			$queryUnpaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
									SUM(MPD.pinjaman_bunga_ammount) total_unpaid_pinjaman 
									FROM member_pinjaman_detail MPD
									WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_UNPAID.'"
									AND MPD.pinjaman_id = "'.$pinjamanId.'"';
		} else {
			$queryPaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
									SUM(MPD.pinjaman_bunga_ammount) total_paid_pinjaman 
									FROM member_pinjaman_detail MPD
									WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_PAID.'"
									AND MPD.member_id = "'.$memberId.'"';

			$queryUnpaidPinjamanMember 	= 'SELECT MPD.id, MPD.member_id, MPD.pinjaman_bunga_ammount, 
									SUM(MPD.pinjaman_bunga_ammount) total_unpaid_pinjaman 
									FROM member_pinjaman_detail MPD
									WHERE pinjaman_payment_status = "'.MemberPinjamanStatus::PINJAMAN_UNPAID.'"
									AND MPD.member_id = "'.$memberId.'"';
		}

		$totalPaidPinjaman		= current($this->q($queryPaidPinjamanMember))->total_paid_pinjaman ?: 0;
		$totalUnpaidPinjaman	= current($this->q($queryUnpaidPinjamanMember))->total_unpaid_pinjaman ?: 0;

		/**
		 * Wrapping all components
		 * */

		$dataPinjaman 	= new stdClass();
		$dataPinjaman->countPaidPinjaman	= $totalPaidPinjaman;
		$dataPinjaman->countUnpaidPinjaman	= $totalUnpaidPinjaman;

		array_push($result, $dataPinjaman);

		return $result;
	}

	public function getMemberPinjamanDetailList($pinjamanId = NULL, $memberId = NULL){

		if ($memberId){
			$queryList 	= 'SELECT * 
							FROM member_pinjaman_detail MPD
							JOIN member_pinjaman MP ON MPD.pinjaman_id = MP.id
							JOIN member ME ON MPD.member_id = ME.id 
							WHERE MPD.pinjaman_id = "'.$pinjamanId.'"
							AND MPD.member_id = "'.$memberId.'"';
		} else {
			$queryList 	= 'SELECT * 
							FROM member_pinjaman_detail MPD
							JOIN member_pinjaman MP ON MPD.pinjaman_id = MP.id
							JOIN member ME ON MPD.member_id = ME.id 
							WHERE MPD.pinjaman_id = "'.$pinjamanId.'"';
		}

		$result = $this->q($queryList);

		return $result;
	}


	/**
	 *
	 *	Setter
	 *
	 **/

	public function setPinjamanMember($params){

		$authService = authenticateMemberTrCode($params["member_id"], $params["member_transaction_code"]);
		switch ($authService['status']){
			case AuthStatus::AUTHORIZED:

				//cek member active status
				$memberStatus = $this->MemberModel->getMemberActiveStatus($params["member_id"]);

				if ($memberStatus["member_is_activated"]){

					$whereLpcId		= array("id"	=> $params["pinjaman_content_id"]);
					$dataLpc		= $this->gw("lentera_pinjaman_content", $whereLpcId);
					$lpcName		= current($dataLpc)->pinjaman_name;
					$lpcTenorType	= current($dataLpc)->pinjaman_content_tenor_type;

					$where 			= array("pinjaman_content_id" 	=> $params["pinjaman_content_id"],
											"member_id"				=> $params["member_id"]);
					$initializeMpc	= $this->cw("member_pinjaman_content", $where);

					if ($initializeMpc == 0){
						$dataScm	= array("member_id"			 	=> $params["member_id"],
											"pinjaman_content_id" 	=> $params["pinjaman_content_id"],
											"created_at"			=> date('Y-m-d H:i:s'),
											"created_by"			=> $params["member_id"]);

						$this->i("member_pinjaman_content", $dataScm);
					}

					$data		= array("member_id"						=> $params["member_id"],
										"pinjaman_content_id"			=> $params["pinjaman_content_id"],
										"pinjaman_content_detail"		=> $params["pinjaman_content_detail"],
										"pinjaman_member_ammount"		=> $params["pinjaman_member_ammount"],
										"pinjaman_member_tenor"			=> $params["pinjaman_member_tenor"],
										"pinjaman_member_tenor_type"	=> $lpcTenorType,
										"pinjaman_transaction_code"		=> $params["pinjaman_transaction_code"],
										"pinjaman_member_description"	=> $params["pinjaman_member_description"],
										"approved_status"				=> MemberPinjamanApproval::PINJAMAN_NEW,
										"created_by"					=> $params["member_id"],
										"created_at"					=> $params["created_at"]);

					$statusAddNewSimpanan = $this->is("member_pinjaman", $data);
					if ($statusAddNewSimpanan == "success"){
						//send report
						$insertedId	= $this->db->insert_id();
						$data 	= array("transaction_id"		=> $insertedId,
										"transaction_input_type"=> TransactionInputType::DEBIT,
										"transaction_type"		=> TransactionType::PINJAMAN_DEBIT);

						$this->ActivityLog->transactionLog($data);

						$reportAmmount 	= number_format(intval($params["pinjaman_member_ammount"]),0,'.','.');
						$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Mengajukan ".$lpcName." Sebesar Rp.".$reportAmmount;
						$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Mengajukan ".$lpcName." Sebesar Rp.".$reportAmmount;

						$data	= array("member_id"				=> $params["member_id"],
										"user_type"				=> CredentialType::MEMBER,
										"receiver"				=> CredentialType::ADMIN,
										"report_description"	=> $reportDescription,
										"notif_description"		=> $notifDescription,
										"reference_link"		=> 'admin/pinjaman/permohonan-pinjaman',
										"created_at"			=> date('Y-m-d H:i:s'));
						$this->ActivityLog->actionLog($data);

						$result = array("status" 	=> "success",
										"data"		=> "Pengajuan ".$lpcName." Anda akan di proses");
					} else {
						$result = array("status" 	=> "failed",
										"data"		=> "Terjadi Kesalahan Saat Mengajukan Pinjaman");
					}

				} else {
					$result = array("status" 	=> "failed",
									"data"		=> "Mohon aktifkan akun anda terlebih dahulu");
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
	}

	public function setPinjamanMemberDetail($params){

		$approvedDate	= date('Y-m-d H:i:s');
		$approvedBy		= $this->session->userdata("admin_id");
		$bungaAmmount 	= intval($params["pinjaman_ammount"] * ($params["pinjaman_bunga"] / 100));
		$dataPinjaman 	= array("approved_status" 	=> $params["pinjaman_status_approval"],
								"approved_date" 	=> $approvedDate,
								"approved_by"		=> $approvedBy);

		$wherePinjamanId = array("id" => $params["pinjaman_id"]);

		$updatedStatus = $this->ud("member_pinjaman", $dataPinjaman, $wherePinjamanId);

		if ($updatedStatus == "success"){
			switch ($params["pinjaman_status_approval"]){
				case MemberPinjamanApproval::PINJAMAN_PROCESSED:

					for ($no = 1; $no <= $params["pinjaman_tenor"]; $no++) {

						switch ($params["pinjaman_tenor_type"]){
							case MemberPinjamanTenorType::TENOR_DAILY:
								$dueDate = date("Y-m-d H:i:s", strtotime("+" . $no . "day", strtotime($approvedDate)));
								break;
							case MemberPinjamanTenorType::TENOR_YEARLY:
								$dueDate = date("Y-m-d H:i:s", strtotime("+" . $no . "year", strtotime($approvedDate)));
								break;
							default:
								$dueDate = date("Y-m-d H:i:s", strtotime("+" . $no . "month", strtotime($approvedDate)));
								break;
						}

						$pinjamanBungaAmmount = (($no == $params["pinjaman_tenor"]) ? intval($params["pinjaman_ammount"] + $bungaAmmount) : $bungaAmmount);

						$dataPinjamanDetail = array("pinjaman_id"						=> $params["pinjaman_id"],
							"member_id"							=> $params["member_id"],
							"pinjaman_detail_transaction_code"	=> "PJ-".generatePaymentTransactionCode($params["member_id"]),
							"pinjaman_detail_payment_code"		=> "PB-".generatePaymentTransactionCode($params["member_id"]),
							"pinjaman_bunga_ammount"			=> $pinjamanBungaAmmount,
							"pinjaman_tenor_number"				=> $no,
							"pinjaman_due_date"					=> $dueDate,
							"pinjaman_payment_status"			=> MemberPinjamanStatus::PINJAMAN_UNPAID,
							"created_at"						=> $approvedDate,
							"created_by"						=> $approvedBy);

						$this->i("member_pinjaman_detail", $dataPinjamanDetail);
					}

					// send report
					$reportDescription  = "Mengupdate status Pinjaman [".$params["pinjaman_transaction_code"]."] menjadi ".MemberPinjamanApproval::PINJAMAN_PROCESSED;
					$notifDescription	= "Pinjaman [".$params["pinjaman_transaction_code"]."] anda telah diproses";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
						"admin_id"				=> $this->session->userdata("admin_id"),
						"user_type"				=> CredentialType::ADMIN,
						"receiver"				=> CredentialType::MEMBER,
						"report_description"	=> $reportDescription,
						"notif_description"		=> $notifDescription,
						"reference_link"		=> 'member/pinjaman/summary',
						"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
						"data"		=> "Status Pinjaman Anggota Berhasil Diubah Menjadi : ".MemberPinjamanApproval::PINJAMAN_PROCESSED);
					break;
				case MemberPinjamanApproval::PINJAMAN_REJECTED:

					// send report
					$reportDescription  = "Mengupdate status Pinjaman [".$params["pinjaman_transaction_code"]."] menjadi ".MemberPinjamanApproval::PINJAMAN_REJECTED;
					$notifDescription	= "Pinjaman [".$params["pinjaman_transaction_code"]."] anda Ditolak";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
						"admin_id"				=> $this->session->userdata("admin_id"),
						"user_type"				=> CredentialType::ADMIN,
						"receiver"				=> CredentialType::MEMBER,
						"report_description"	=> $reportDescription,
						"notif_description"		=> $notifDescription,
						"reference_link"		=> 'member/pinjaman/summary',
						"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
						"data"		=> "Status Pinjaman Anggota Berhasil Diubah Menjadi : ".MemberPinjamanApproval::PINJAMAN_REJECTED);
					break;
				case MemberPinjamanApproval::PINJAMAN_CANCEL:

					// send report
					$reportDescription  = "Mengupdate status Pinjaman [".$params["pinjaman_transaction_code"]."] menjadi ".MemberPinjamanApproval::PINJAMAN_CANCEL;
					$notifDescription	= "Pinjaman [".$params["pinjaman_transaction_code"]."] anda Dibatalkan";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
						"admin_id"				=> $this->session->userdata("admin_id"),
						"user_type"				=> CredentialType::ADMIN,
						"receiver"				=> CredentialType::MEMBER,
						"report_description"	=> $reportDescription,
						"notif_description"		=> $notifDescription,
						"reference_link"		=> 'member/pinjaman/summary',
						"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
						"data"		=> "Status Pinjaman Anggota Berhasil Diubah Menjadi : ".MemberPinjamanApproval::PINJAMAN_CANCEL);
					break;
				case MemberPinjamanApproval::PINJAMAN_PENDING:

					// send report
					$reportDescription  = "Mengupdate status Pinjaman [".$params["pinjaman_transaction_code"]."] menjadi ".MemberPinjamanApproval::PINJAMAN_PENDING;
					$notifDescription	= "Pinjaman [".$params["pinjaman_transaction_code"]."] anda Ditunda";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
						"admin_id"				=> $this->session->userdata("admin_id"),
						"user_type"				=> CredentialType::ADMIN,
						"receiver"				=> CredentialType::MEMBER,
						"report_description"	=> $reportDescription,
						"notif_description"		=> $notifDescription,
						"reference_link"		=> 'member/pinjaman/summary',
						"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
						"data"		=> "Status Pinjaman Anggota Berhasil Diubah Menjadi : ".MemberPinjamanApproval::PINJAMAN_PENDING);
					break;
				default:
					$result = array("status" 	=> "failed",
									"data"		=> "Anda Belum Merubah Status Pinjaman Anggota");
					break;
			}
		} else {
			$result = array("status" 	=> "failed",
							"data"		=> "Terjadi Kesalahan Saat Mengaupdate Status Pinjaman Anggota. Silahkan Menghubungi Tim IT Anda!");
		}

		return $result;
	}

	public function setBayarPinjamanDetail($params){

		$whereId	= array("id" => $params["id"]);
		$getDate	= date('Y-m-d H:i:s');
		$dataPaid 	= array("pinjaman_payment_status"	=> MemberPinjamanStatus::PINJAMAN_PAID,
							"pinjaman_payment_date"		=> $getDate,
							"updated_at"				=> $getDate,
							"updated_by"				=> $this->session->userdata("member_id"));

		$updatedStatus = $this->ud("member_pinjaman_detail", $dataPaid, $whereId);

		if ($updatedStatus == "success"){

			// send report
			$data 	= array("transaction_id"		=> $params["id"],
							"transaction_input_type"=> TransactionInputType::CREDIT,
							"transaction_type"		=> TransactionType::PINJAMAN_CREDIT);

			$this->ActivityLog->transactionLog($data);

			$reportAmmount 	= number_format(intval($params["pinjaman_bunga_ammount"]),0,'.','.');
			$reportDescription  = "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar Pinjaman Sebesar Rp.".$reportAmmount;
			$notifDescription	= "[".$this->MemberModel->getMemberReferalCode($params["member_id"])."] Membayar Pinjaman Sebesar Rp.".$reportAmmount;

			$data	= array("member_id"				=> $params["member_id"],
							"user_type"				=> CredentialType::MEMBER,
							"receiver"				=> CredentialType::ADMIN,
							"report_description"	=> $reportDescription,
							"notif_description"		=> $notifDescription,
							"reference_link"		=> 'admin/pinjaman/pembayaran-pinjaman',
							"created_at"			=> date('Y-m-d H:i:s'));

			$this->ActivityLog->actionLog($data);

			$result = array("status" 	=> "success",
							"data"		=> "Terima Kasih Telah Membayar Tagihan Anda");

		} else {
			$result = array("status" 	=> "failed",
							"data"		=> "Harap Membayar Tagihan Sebelumnya");
		}

		return $result;
	}

}
