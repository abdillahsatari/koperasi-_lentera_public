<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends CI_Controller {

	// for testing only
	public function index(){

		$content	= '_memberLayouts/testingCronJobs/index';
		$memberId	= $this->session->userdata("member_id");
		$dataReportTabungan = $this->TabunganModel->getAllTabunganMemberReportList(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA, NULL);
		$data				= array('session' 				=> SessionType::MEMBER,
									'dataTotalTabungan'		=> current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance,
									'dataTotalImbalJasa'	=> current($this->FinanceModel->memberFinance($memberId))->countTabunganImbalJasa,
									'pageType'				=> MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA,
									'dataReportTabungan'	=> $dataReportTabungan,
									'content'				=> $content);

		$this->load->view('_memberLayouts/wrapper', $data);
	}

	// actual jobs
	public function doJobsTabunganImbalJasa(){

		/**
		 *
		 *	cron everyMonth
		 *
		 * */

		$whereId				= array("id" => 1);
		$tabunganMemberSetting	= current($this->CrudModel->gw("tabungan_setting",$whereId));

		$query = 'SELECT ME.id, ME.member_full_name, ME. member_referal_code
					FROM member ME 
					WHERE EXISTS(SELECT TM.id, TM.member_id FROM tabungan_member TM WHERE TM.member_id = ME.id)';
		$memberWithTabungan = $this->CrudModel->q($query);

		foreach ($memberWithTabungan as $member){
			$memberId			= $member->id;
			$tijTotalTabungan 	= current($this->FinanceModel->memberFinance($memberId))->totalTabunganBalance;
			$tijAmmount			= $tijTotalTabungan * ($tabunganMemberSetting->interest_percentage / 100);
			$tijAdminFee		= $tabunganMemberSetting->admin_fee;
			$tijAccumulation	= $tijAmmount - $tijAdminFee;
			$tijTrCode			= "TIJ-".generatePaymentTransactionCode($memberId);
			$tijCreatedAt		= date('Y-m-d H:i:s');

			$dataTij	= array("member_id" 			=> $memberId,
								"tij_total_tabungan"	=> $tijTotalTabungan,
								"tij_ammount"			=> $tijAmmount,
								"tij_Admin_fee"			=> $tijAdminFee,
								"tij_accumulation"		=> $tijAccumulation,
								"tij_transfer_code"		=> $tijTrCode,
								"created_at"			=> $tijCreatedAt);

			$returnId = $this->CrudModel->i2("tabungan_imbal_jasa", $dataTij);

			//send report
//			$dataTf	= array("transaction_id"		=> $returnId,
//							"transaction_input_type"=> TransactionInputType::DEBIT,
//							"transaction_type"		=> TransactionType::TABUNGAN_CREDIT);
//
//			$this->ActivityLog->transactionLog($dataTf);

			$reportDescription  = "Mendapat Tambahan Saldo Dompet Dari Imbal Jasa [".$tijTrCode."] Program Tabungan";
			$notifDescription	= "Anda Mendapat Tambahan Saldo Dompet dari Imbal Jasa Tabungan Anda [".$tijTrCode."]";
			$dataActionLog		= array("member_id"				=> $memberId,
										"user_type"				=> CredentialType::AUTOMATIC_SYSTEM,
										"receiver"				=> CredentialType::MEMBER,
										"report_description"	=> $reportDescription,
										"notif_description"		=> $notifDescription,
										"reference_link"		=> 'member/tabungan/laporan?type='. strtolower(MemberTabunganReportType::TABUNGAN_REPORT_IMBAL_JASA),
										"created_at"			=> date('Y-m-d H:i:s'));

			$this->ActivityLog->actionLog($dataActionLog);

		}
	}

	public function doJobsSimpananExpiredTenor(){

		/**
		 *
		 *	cron everyday
		 *
		 * */

		$simpananExpiredate = $this->CrudModel->gw("simpanan_member", "simpanan_expired_date IS NOT NULL AND simpanan_is_expired IS NULL");
		$result = array();

		foreach($simpananExpiredate as $simpanan){

			$query 				= 'SELECT SM.*, ME.member_referal_code FROM simpanan_member SM JOIN member ME ON SM.member_id = ME.id WHERE SM.id = "'.$simpanan->id.'"';
			$dataSimpananMember	= current($this->CrudModel->q($query));

			$isToday 				= date("Y-m-d");
			$simpananExpiredDate	= date("Y-m-d", strtotime($dataSimpananMember->simpanan_expired_date));
			$simpananId 			= $dataSimpananMember->id;
			$simpananMemberId		= $dataSimpananMember->member_id;
			$simpananAmmount		= $dataSimpananMember->simpanan_member_ammount;

			if ($simpananExpiredDate == $isToday) {

				$whereId = array("id" => $simpanan->id);
				$dataUpdate = array("simpanan_is_expired" => true);;

				$this->CrudModel->u("simpanan_member", $dataUpdate, $whereId);

				//send report
				$data 	= array("transaction_id"		=> $simpananId,
								"transaction_input_type"=> TransactionInputType::DEBIT,
								"transaction_type"		=> TransactionType::SIMPANAN);

				$this->ActivityLog->transactionLog($data);

				$dataDescription  	= "Masa Kontrak ".$dataSimpananMember->simpanan_lentera_content_name." [".$dataSimpananMember->simpanan_transaction_code."] Telah Berakhir.";
				$notifDescription	= "Masa Kontrak ".$dataSimpananMember->simpanan_lentera_content_name." [".$dataSimpananMember->simpanan_transaction_code."] Telah Berakhir.";

				$data	= array("member_id"				=> $simpananMemberId,
								"user_type"				=> CredentialType::AUTOMATIC_SYSTEM,
								"receiver"				=> CredentialType::MEMBER,
								"report_description"	=> $dataDescription,
								"notif_description"		=> $notifDescription,
								"reference_link"		=> 'member/dashboard',
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);

			}
		}
	}

	public function doJobsSimpananMemberImbalJasa(){

		/**
		 *
		 *	cron everyday
		 *
		 * */

		$inactiveSij = $this->CrudModel->gw("simpanan_imbal_jasa", "sij_is_active IS NULL");
		$result = array();

		foreach($inactiveSij as $sij){

			$query 			= 'SELECT SIJ.*, ME.member_referal_code FROM simpanan_imbal_jasa SIJ JOIN member ME ON SIJ.member_id = ME.id WHERE SIJ.id = "'.$sij->id.'"';
			$dataSijMember	= current($this->CrudModel->q($query));

			$isToday 		= date("Y-m-d");
			$sijActiveDate	= date("Y-m-d", strtotime($dataSijMember->sij_active_date));
			$sijId 			= $dataSijMember->id;
			$sijMemberId	= $dataSijMember->member_id;

			if ($sijActiveDate == $isToday) {

				$whereId = array("id" => $sijId);
				$dataUpdate = array("sij_is_active" => true);;

				$this->CrudModel->u("simpanan_imbal_jasa", $dataUpdate, $whereId);

				//send report
				$data 	= array("transaction_id"		=> $sijId,
								"transaction_input_type"=> TransactionInputType::DEBIT,
								"transaction_type"		=> TransactionType::SIMPANAN_IMBAL_JASA);

				$this->ActivityLog->transactionLog($data);

				$dataDescription  	= "Mendapat Imbal Jasa Dari ".$dataSijMember->simpanan_content_name." [".$dataSijMember->sij_transaction_code."]";
				$notifDescription	= "Anda Memperoleh Imbal Jasa Dari ".$dataSijMember->simpanan_content_name." [".$dataSijMember->sij_transaction_code."]";

				$data	= array("member_id"				=> $sijMemberId,
								"user_type"				=> CredentialType::AUTOMATIC_SYSTEM,
								"receiver"				=> CredentialType::MEMBER,
								"report_description"	=> $dataDescription,
								"notif_description"		=> $notifDescription,
								"reference_link"		=> 'member/dashboard',
								"created_at"			=> date('Y-m-d H:i:s'));

				$this->ActivityLog->actionLog($data);
			}
		}
	}

}
