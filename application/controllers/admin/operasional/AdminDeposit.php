<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminDeposit extends CI_Controller{

	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED) {
			redirect('pengurus/login');
		}
	}

	public function index(){

		$pageType	= urldecode($this->uri->segment(3));
		$content	= '_adminLayouts/deposit/index';

		switch ($pageType){
			case strtolower(MemberDepositStatus::DEPOSIT_NEW):
				$query					= 'SELECT MD.*, LBA.nama_bank, ME.member_referal_code, ME.member_full_name FROM member_deposit MD
											JOIN member ME ON MD.deposit_member_id = ME.id
											JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id = LBA.id
											WHERE MD.deposit_status = "'.MemberDepositStatus::DEPOSIT_NEW.'"';
				$dataDeposit			= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataDeposit'	=> $dataDeposit,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberDepositStatus::DEPOSIT_PROCESSED):
				$query					= 'SELECT MD.*, LBA.nama_bank, ME.member_referal_code, ME.member_full_name FROM member_deposit MD
											JOIN member ME ON MD.deposit_member_id = ME.id
											JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id = LBA.id
											WHERE MD.deposit_status = "'.MemberDepositStatus::DEPOSIT_PROCESSED.'" ';
				$dataDeposit			= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataDeposit'	=> $dataDeposit,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;

			case strtolower(MemberDepositStatus::DEPOSIT_PENDING):
				$query					= 'SELECT MD.*, LBA.nama_bank, ME.member_referal_code, ME.member_full_name FROM member_deposit MD
											JOIN member ME ON MD.deposit_member_id = ME.id
											JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id = LBA.id
											WHERE MD.deposit_status = "'.MemberDepositStatus::DEPOSIT_PENDING.'"';
				$dataDeposit			= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataDeposit'	=> $dataDeposit,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'content'		=> $content);

				$this->load->view('_adminLayouts/wrapper', $data);
				break;
			case strtolower(MemberDepositStatus::DEPOSIT_CANCEL):
				$query			= 'SELECT MD.*, LBA.nama_bank, ME.member_referal_code, ME.member_full_name FROM member_deposit MD
									JOIN member ME ON MD.deposit_member_id = ME.id
									JOIN lentera_bank_account LBA ON MD.deposit_lentera_bank_id = LBA.id
									WHERE MD.deposit_status = "'.MemberDepositStatus::DEPOSIT_NEW.'"';
				$dataDeposit	= $this->CrudModel->q($query);

				$data	= array('session' 		=> SessionType::ADMIN,
								'pageType'		=> $pageType,
								'dataDeposit'	=> $dataDeposit,
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

		$this->form_validation->set_rules('deposit_status', 'Status Deposit', 'trim|required');

		if ($this->form_validation->run() == false || $input["deposit_status"] == MemberDepositStatus::DEPOSIT_NEW) {
			switch ($input["deposit_status"]){
				case MemberDepositStatus::DEPOSIT_NEW:
					generalAllert("Woops!", "Anda belum merubah status deposit",AllertType::INFO);
					break;
				default:
					generalAllert("Perubahan Status Gagal!". "<br/>", validation_errors(), AllertType::FAILED);
					break;
			}
			$dataredirect = "admin/deposit/" . strtolower(MemberDepositStatus::DEPOSIT_NEW);
			redirect($dataredirect);
		} else {
			$dataDeposit	= array("deposit_status"	=> $input["deposit_status"],
				"updated_at"		=> date('Y-m-d H:i:s'),
				"updated_by"		=> $adminId);

			$whereId	= array("id" => $input["deposit_id"]);
			$depositUpdatedStatus = $this->CrudModel->ud("member_deposit", $dataDeposit, $whereId);

			if ($depositUpdatedStatus == 'success') {

				$deposit 	= current($this->CrudModel->gw("member_deposit", $whereId));

				switch ($input["deposit_status"]){
					case MemberDepositStatus::DEPOSIT_PROCESSED:
						$data 	= array("transaction_id"		=> $input["deposit_id"],
							"transaction_input_type"=> TransactionInputType::DEBIT,
							"transaction_type"		=> TransactionType::DEPOSIT);
						$this->ActivityLog->transactionLog($data);

						$reportDescription  = "Mengupdate status deposit [".$deposit->deposit_transaction_code."] menjadi ".MemberDepositStatus::DEPOSIT_PROCESSED ;
						$notifDescription	= "Deposit [".$deposit->deposit_transaction_code."] anda telah disetujui";
						$dataActionLog		= array("member_id"				=> $deposit->deposit_member_id,
							"admin_id"				=> $this->session->userdata("admin_id"),
							"user_type"				=> CredentialType::ADMIN,
							"receiver"				=> CredentialType::MEMBER,
							"report_description"	=> $reportDescription,
							"notif_description"		=> $notifDescription,
							"reference_link"		=> 'member-deposit',
							"created_at"			=> date('Y-m-d H:i:s'));

						$this->ActivityLog->actionLog($dataActionLog);
						generalAllert("Update Data Berhasil!", "Status deposit anggota berhasil diperbaharui.", AllertType::SUCCESS);
						break;
					case MemberDepositStatus::DEPOSIT_CANCEL:

						$reportDescription  = "Mengupdate status deposit [".$deposit->deposit_transaction_code."] menjadi ".MemberDepositStatus::DEPOSIT_CANCEL ;
						$notifDescription	= "Deposit [".$deposit->deposit_transaction_code."] anda telah dibatalkan";
						$dataActionLog		= array("member_id"				=> $deposit->deposit_member_id,
							"admin_id"				=> $this->session->userdata("admin_id"),
							"user_type"				=> CredentialType::ADMIN,
							"receiver"				=> CredentialType::MEMBER,
							"report_description"	=> $reportDescription,
							"notif_description"		=> $notifDescription,
							"reference_link"		=> 'member-deposit',
							"created_at"			=> date('Y-m-d H:i:s'));

						$this->ActivityLog->actionLog($dataActionLog);
						generalAllert("Update Data Berhasil!", "Status deposit anggota berhasil diperbaharui.", AllertType::SUCCESS);
						break;
					case  MemberDepositStatus::DEPOSIT_PENDING:
						$reportDescription  = "Mengupdate status deposit [".$deposit->deposit_transaction_code."] menjadi ".MemberDepositStatus::DEPOSIT_PENDING ;
						$notifDescription	= "Deposit [".$deposit->deposit_transaction_code."] anda telah dipending";
						$dataActionLog		= array("member_id"				=> $deposit->deposit_member_id,
							"admin_id"				=> $this->session->userdata("admin_id"),
							"user_type"				=> CredentialType::ADMIN,
							"receiver"				=> CredentialType::MEMBER,
							"report_description"	=> $reportDescription,
							"notif_description"		=> $notifDescription,
							"reference_link"		=> 'member-deposit',
							"created_at"			=> date('Y-m-d H:i:s'));

						$this->ActivityLog->actionLog($dataActionLog);
						generalAllert("Update Data Berhasil!", "Status deposit anggota berhasil diperbaharui.", AllertType::SUCCESS);
						break;
					default:
						generalAllert("Opps!", "Anda belum merubah status deposit anggota.", AllertType::FAILED);
				}

			} else {
				generalAllert("Update Data Gagal!", "Terjadi kesalahan saat melakukan perubahan data status deposit anggota. Silahkan hubungi developer IT anda.", AllertType::FAILED);
			}

			$dataUrl = "admin/deposit/".strtolower($input["deposit_status"]);
			redirect($dataUrl);
		}
	}
}
