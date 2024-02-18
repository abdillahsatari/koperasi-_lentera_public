<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TabunganModel extends CrudModel {

	/**
	 *
	 *	Getter
	 *
	 **/
	public function getAllTabunganMemberList($tabunganApproval = NULL, $memberId = NULL){

		if ($tabunganApproval) {

			if ($memberId){
				$query = 'SELECT TM.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member TM 
						JOIN member ME ON TM.member_id = ME.id 
						WHERE TM.tabungan_approval = "'.$tabunganApproval.'"
						AND TM.member_id = "'.$memberId.'"';
			} else {
				$query = 'SELECT TM.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member TM 
						JOIN member ME ON TM.member_id = ME.id
						WHERE TM.tabungan_approval = "'.$tabunganApproval.'"';
			}

		} else {

			if ($memberId){
				$query = 'SELECT TM.*, ME.member_full_name, ME.member_referal_code, LBA.nama_bank, LBA.nomor_rekening, LBA.nama_pemilik_account
						FROM tabungan_member TM 
						JOIN member ME ON TM.member_id = ME.id
						JOIN lentera_bank_account LBA ON TM.lentera_bank_id = LBA.id 
						WHERE TM.member_id = "'.$memberId.'"';
			} else {
				$query = 'SELECT TM.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member TM 
						JOIN member ME ON TM.member_id = ME.id';
			}
		}

		return $this->q($query);

	}

	public function getAllTabunganTfMemberList($tabunganTfApproval = NULL, $memberId = NULL){
		if ($tabunganTfApproval) {
			if ($memberId){
				$query = 'SELECT TMT.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member_transfer TMT
						JOIN member ME ON TMT.member_id = ME.id 
						WHERE TMT.tabungan_tf_approval = "'.$tabunganTfApproval.'"
						AND TMT.member_id = "'.$memberId.'"';
			} else {
				$query = 'SELECT TMT.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member_transfer TMT
						JOIN member ME ON TMT.member_id = ME.id 
						WHERE tabungan_tf_approval = "'.$tabunganTfApproval.'"';
			}
		} else {
			if ($memberId){
				$query = 'SELECT TMT.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member_transfer TMT 
						JOIN member ME ON TMT.member_id = ME.id
						WHERE TMT.member_id = "'.$memberId.'"';
			} else {
				$query = 'SELECT TMT.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_member_transfer TMT 
						JOIN member ME ON TMT.member_id = ME.id';
			}
		}

		return $this->q($query);
	}

	public function getAllTabunganMemberReportList($reportType = NULL, $memberId = NULL){

		if ($reportType == MemberTabunganReportType::TABUNGAN_REPORT_MUTASI_TABUNGAN){

			if ($memberId){
				$query = 'SELECT TL.*, ME.member_full_name, ME.member_referal_code 
						FROM transaction_log TL JOIN member ME ON TL.member_id = ME.id 
						WHERE TL.transaction_type = "'.TransactionType::TABUNGAN_DEBIT.'" OR TL.transaction_type = "'.TransactionType::TABUNGAN_CREDIT.'"
						AND TL.member_id = "'.$memberId.'" ORDER BY TL.created_at DESC';
			} else {
				$query = 'SELECT TL.*, ME.member_full_name, ME.member_referal_code 
						FROM transaction_log TL JOIN member ME ON TL.member_id = ME.id 
						WHERE TL.transaction_type = "'.TransactionType::TABUNGAN_DEBIT.'" OR TL.transaction_type = "'.TransactionType::TABUNGAN_CREDIT.'" ORDER BY TL.created_at DESC';
			}

		} else {

			if ($memberId){
				$query = 'SELECT TIJ.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_imbal_jasa TIJ JOIN member ME ON TIJ.member_id = ME.id
						WHERE TIJ.member_id = "'.$memberId.'" ORDER BY TIJ.created_at DESC';
			} else {
				$query = 'SELECT TIJ.*, ME.member_full_name, ME.member_referal_code 
						FROM tabungan_imbal_jasa TIJ JOIN member ME ON TIJ.member_id = ME.id ORDER BY TIJ.created_at DESC';
			}
		}

		return $this->q($query);
	}

	/**
	 *
	 *	Setter
	 *
	 **/
	public function setTabunganMemberApproval($params){
		$whereId 	= array("id" => $params["tabungan_id"]);
		$data		= array("tabungan_approval" 		=> $params["tabungan_approval"],
							"tabungan_approved_date"	=> $params["tabungan_approved_date"],
							"tabungan_approved_by"		=> $params["tabungan_approved_by"],
							"updated_at"				=> $params["updated_at"]);

		$updatedStatus = $this->ud("tabungan_member", $data, $whereId);

		if ($updatedStatus == "success") {

			switch ($params["tabungan_approval"]){
				case MemberTabunganApproval::TABUNGAN_PROCESSED:
					//send report
					$data 	= array("transaction_id"		=> $params["tabungan_id"],
									"transaction_input_type"=> TransactionInputType::DEBIT,
									"transaction_type"		=> TransactionType::TABUNGAN_DEBIT);

					$this->ActivityLog->transactionLog($data);

					$reportDescription  = "Mengupdate status Tabungan [".$params["tabungan_tr_code"]."] menjadi ".MemberTabunganApproval::TABUNGAN_PROCESSED;
					$notifDescription	= "Tabungan [".$params["tabungan_tr_code"]."] anda Telah Diproses";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/tabungan/summary',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
									"data"		=> "Status Tabungan Anggota Berhasil Diubah Menjadi : ".MemberTabunganApproval::TABUNGAN_PROCESSED);
					break;
				case MemberTabunganApproval::TABUNGAN_REJECTED:

					//send report
					$reportDescription  = "Mengupdate status Tabungan [".$params["tabungan_tr_code"]."] menjadi ".MemberTabunganApproval::TABUNGAN_REJECTED;
					$notifDescription	= "Tabungan [".$params["tabungan_tr_code"]."] anda Telah Ditolak";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/tabungan/summary',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);
					$result = array("status" 	=> "success",
									"data"		=> "Status Tabungan Anggota Berhasil Diubah Menjadi : ".MemberTabunganApproval::TABUNGAN_REJECTED);
					break;
			}

		} else {
			$result = array("status" 	=> "failed",
							"data"		=> "Terjadi kesalahan saat merubah status tabungan [".$params["tabungan_tr_code"]."]. Silahkan coba lagi");
		}

		return $result;

	}

	public function setTabunganMemberTfApproval($params){
		$whereId 	= array("id" => $params["tabungan_tf_id"]);
		$data		= array("tabungan_tf_approval" 		=> $params["tabungan_tf_approval"],
							"tabungan_tf_approved_date"	=> $params["tabungan_tf_approved_date"],
							"tabungan_tf_approved_by"	=> $params["tabungan_tf_approved_by"],
							"updated_at"				=> $params["updated_at"]);

		$updatedStatus = $this->ud("tabungan_member_transfer", $data, $whereId);

		if ($updatedStatus == "success") {

			switch ($params["tabungan_tf_approval"]){
				case MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED:
					//send report
					$dataTf	= array("transaction_id"		=> $params["tabungan_tf_id"],
									"transaction_input_type"=> TransactionInputType::CREDIT,
									"transaction_type"		=> TransactionType::TABUNGAN_CREDIT);

					$this->ActivityLog->transactionLog($dataTf);

					$reportDescription  = "Mengupdate status Transfer Tabungan [".$params["tabungan_tf_tr_code"]."] menjadi ".MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED;
					$notifDescription	= "Permintaan Transfer Tabungan [".$params["tabungan_tf_tr_code"]."] anda Telah Diproses";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/tabungan/daftar-pemindahan',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);

					$result = array("status" 	=> "success",
									"data"		=> "Status Transfer Tabungan Anggota Berhasil Diubah Menjadi : ".MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED);
					break;
				case MemberTabunganApproval::TABUNGAN_REJECTED:

					//send report
					$reportDescription  = "Mengupdate status Transfer Tabungan [".$params["tabungan_tf_tr_code"]."] menjadi ".MemberTabunganTfApproval::TABUNGAN_TF_REJECTED;
					$notifDescription	= "Permintaan Transfer Tabungan [".$params["tabungan_tr_code"]."] anda Telah Ditolak";
					$dataActionLog		= array("member_id"				=> $params["member_id"],
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/tabungan/daftar-pemindahan',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);
					$result = array("status" 	=> "success",
									"data"		=> "Status Transfer Tabungan Anggota Berhasil Diubah Menjadi : ".MemberTabunganTfApproval::TABUNGAN_TF_REJECTED);
					break;
			}

		} else {
			$result = array("status" 	=> "failed",
							"data"		=> "Terjadi kesalahan saat merubah status transfer tabungan [".$params["tabungan_tf_tr_code"]."]. Silahkan coba lagi");
		}

		return $result;
	}

}
