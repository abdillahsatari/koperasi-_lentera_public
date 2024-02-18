<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminWithdrawal extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){

		$pageType	= urldecode($this->uri->segment(3));
		$content	= '_adminLayouts/withdrawal/index';

		switch ($pageType){
			case strtolower(MemberWdStatus::WD_NEW):
				$query					= 'SELECT WM.*, ME.member_referal_code, ME.member_full_name, MBA.bank_account_name
											FROM withdrawal_member WM
											JOIN member ME ON WM.wd_member_id = ME.id
											JOIN member_bank_account MBA ON WM.wd_member_bank_id = MBA.id
											WHERE WM.wd_status = "'.MemberWdStatus::WD_NEW.'" ORDER BY created_at DESC';
				$dataWithdrawal			= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataWithdrawal'=> $dataWithdrawal,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberWdStatus::WD_PROCESSED):
				$query					= 'SELECT WM.*, ME.member_referal_code, ME.member_full_name, MBA.bank_account_name
											FROM withdrawal_member WM
											JOIN member ME ON WM.wd_member_id = ME.id
											JOIN member_bank_account MBA ON WM.wd_member_bank_id = MBA.id
											WHERE WM.wd_status = "'.MemberWdStatus::WD_PROCESSED.'" ';
				$dataWithdrawal			= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataWithdrawal'=> $dataWithdrawal,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;

			case strtolower(MemberWdStatus::WD_CANCEL):
				$query					= 'SELECT WM.*, ME.member_referal_code, ME.member_full_name, MBA.bank_account_name
											FROM withdrawal_member WM
											JOIN member ME ON WM.wd_member_id = ME.id
											JOIN member_bank_account MBA ON WM.wd_member_bank_id = MBA.id
											WHERE WM.wd_status = "'.MemberWdStatus::WD_CANCEL.'"';
				$dataWithdrawal			= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataWithdrawal'=> $dataWithdrawal,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			default :
				$this->load->view('errors/html/error_404');
				break;
		}
	}

	public function update(){
		$input 			= $this->input->post(NULL, TRUE);
		$adminId		= $this->session->userdata('admin_id');
		$dataWithdrawal	= array("wd_status"			=> $input["wd_status"],
								"wd_date_verified"	=> date('Y-m-d H:i:s'),
								"wd_verified_by"	=> $adminId,
								"updated_at"		=> date('Y-m-d H:i:s'),
								"updated_by"		=> $adminId);

		$whereId			= array("id" => $input["withdrawal_id"]);
		$wdUpdatedStatus 	= $this->CrudModel->ud("withdrawal_member", $dataWithdrawal, $whereId);
		$dataUrl 			= "admin/withdrawal/".strtolower($input["suffix"]);

		if ($wdUpdatedStatus == 'success') {
			$withdrawal 	= current($this->CrudModel->gw("withdrawal_member", $whereId));

			switch ($input["wd_status"]){
				case MemberWdStatus::WD_PROCESSED:
					$data 	= array("transaction_id"		=> $input["withdrawal_id"],
									"transaction_input_type"=> TransactionInputType::CREDIT,
									"transaction_type"		=> TransactionType::WITHDRAWAL);
					$this->ActivityLog->transactionLog($data);

					$reportDescription  = "Mengupdate status withdrawal [".$withdrawal->wd_transaction_code."] menjadi ".MemberWdStatus::WD_PROCESSED;
					$notifDescription	= "Withdrawal [".$withdrawal->wd_transaction_code."] anda telah disetujui";
					$dataActionLog		= array("member_id"				=> $withdrawal->wd_member_id,
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/withdrawal/index',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);
					generalAllert("Update Data Berhasil!", "Status withdrawal anggota berhasil diperbaharui.", AllertType::SUCCESS);
					break;
				case MemberWdStatus::WD_CANCEL:

					$reportDescription  = "Mengupdate status withdrawal [".$withdrawal->deposit_transaction_code."] menjadi ".MemberWdStatus::WD_CANCEL;
					$notifDescription	= "Withdrawal [".$withdrawal->deposit_transaction_code."] anda telah dibatalkan";
					$dataActionLog		= array("member_id"				=> $withdrawal->wd_member_id,
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/withdrawal/index',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);
					generalAllert("Update Data Berhasil!", "Status withdrawal anggota berhasil diperbaharui.", AllertType::SUCCESS);
					break;
				case  MemberWdStatus::WD_PENDING:
					$reportDescription  = "Mengupdate status withdrawal [".$withdrawal->deposit_transaction_code."] menjadi ".MemberWdStatus::WD_PENDING;
					$notifDescription	= "Withdrawal [".$withdrawal->deposit_transaction_code."] anda telah dipending";
					$dataActionLog		= array("member_id"				=> $withdrawal->wd_member_id,
												"admin_id"				=> $this->session->userdata("admin_id"),
												"user_type"				=> CredentialType::ADMIN,
												"receiver"				=> CredentialType::MEMBER,
												"report_description"	=> $reportDescription,
												"notif_description"		=> $notifDescription,
												"reference_link"		=> 'member/withdrawal/index',
												"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($dataActionLog);
					generalAllert("Update Data Berhasil!", "Status withdrawal anggota berhasil diperbaharui.", AllertType::SUCCESS);
					break;
				default:
					generalAllert("Opps!", "Anda belum merubah status withdrawal anggota.", AllertType::FAILED);
			}

		} else {
			generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data status withdrawal anggota. Silahkan hubungi developer IT anda.", AllertType::FAILED);
		}

		redirect($dataUrl);

	}
}
