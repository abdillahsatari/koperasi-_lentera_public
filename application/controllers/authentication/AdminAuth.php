<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class AdminAuth extends CI_Controller {

	public function login() {

		if ($this->session->userdata('admin_authStatus') == AuthStatus::AUTHORIZED){
			redirect('admin/dashboard');
		} else {
			$data	= array("session"	=> SessionType::ADMIN);
			$this->load->view('authentication/index', $data);
		}
	}

	public function authentication(){
		$input		= $this->input->post(NULL, TRUE);
		$this->form_validation->set_rules('uid', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false){
			$this->login();
		} else{

			$suffix		= CredentialType::ADMIN;
			$email 		= $input['uid'];
			$password 	= $input['password'];

			$authService 	= authenticate($email, $password, $suffix);
			$dataMsgInfo	= $authService['message']['info'];
			$dataMsg		= $authService['message']['msg'];
			$dataMsgType	= $authService['message']['type'];

			generalAllert($dataMsgInfo, $dataMsg, $dataMsgType);

			if ($authService['status'] == AuthStatus::AUTHORIZED) {

				$this->session->set_userdata($authService['dataSession']);

//				switch ($this->session->userdata["admin_role_type"]){
//					case AdminRoleType::ADMIN:
				$dataRedirect = 'admin/dashboard';
//						break;
//					// Add another admin level here;
//				}

//				echo $this->session->userdata();
			} else {
				$dataRedirect ='pengurus/login';
			};

			redirect($dataRedirect);
		}
	}

	public function verify(){
		$adminEmail				= $this->input->get('email');
		$adminToken				= $this->input->get('token');
		$adminPassword			= $this->input->get('password');
		$adminPhone				= $this->input->get('phone_number');
		$adminTransactionCode	= $this->input->get('transaction_code');

		$query			= 'SELECT admin_token.*, admin.admin_email, admin.admin_full_name, admin.created_at as admin_created_at FROM admin
						   JOIN admin_token ON admin_token.admin_id = admin.id
						   WHERE admin.admin_email = "'.$adminEmail.'" AND admin_token.token = "'.$adminToken.'"';
		$dataVerify		= $this->CrudModel->q($query);

		if (count($dataVerify) > 0) {
			foreach($dataVerify as $data){

				if (time() - $data->created_at < (60 * 60 * 24)) {
					$adminId			= array('id'	=> $data->admin_id);
					$adminDataVerified	= array('admin_is_verified'	=> true,
												'updated_at'		=> date('Y-m-d H:i:s'));

					$this->CrudModel->u('admin', $adminDataVerified, $adminId);

					$reportDescription  = "Melakukan verifikasi email";

					$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
									"user_type"				=> CredentialType::ADMIN,
									"report_description"	=> $reportDescription,
									"created_at"			=> date('Y-m-d H:i:s'));

					$this->ActivityLog->actionLog($data);

					generalAllert("Verifikasi Berhasil!", "Akun Anda Telah Diaktifkan !.", AllertType::SUCCESS);

				} else {

					$newToken		= generateToken();
					$tokenCreated	= time();
					$tokenId		= array('id'		=> $data->id);
					$dataToken		= array('admin_id'	=> $data->admin_id,
						'token'		=> $newToken,
						'created_at'=> $tokenCreated);

					$this->CrudModel->i('admin_token', $dataToken);
					$this->CrudModel->d('admin_token', $tokenId);

					$dataSendEmail	= array('adminFullName'			=> $data->admin_full_name,
						'emailRecipient'		=> $data->admin_email,
						'adminPhoneNumber'		=> $adminPhone,
						'adminPassword'			=> $adminPassword,
						'adminTransactionCode'	=> $adminTransactionCode,
						'adminToken'			=> $newToken,
						'emailType'				=> EmailType::NEW_ADMIN_REGISTRATION,
						'emailSubject'			=> SubjectEmailType::VERIFIKASI_EMAIL);

					$emailService = sendEmail($dataSendEmail);

					switch ($emailService['isDelivered']){
						case TRUE:
							generalAllert("Verifikasi Gagal!", "Batas Verifikasi Telah Berakhir. Email Verifikasi Baru Telah Terkirim Ke Email Anda. Silahkan Lakukan Verifikasi Ulang ", AllertType::FAILED);
							break;
						case FALSE:
							generalAllert("Verifikasi Gagal!", "Batas Verifikasi Telah Berakhir. Tidak Dapat Mengirim Ulang Email Verifikasi. Silahkan Hubungi Pengurus", AllertType::FAILED);
							break;
					};
				}
			}
		} else {
			generalAllert("Verifikasi Gagal!", "Akun Belum Terdaftar", AllertType::FAILED);
		}

		redirect("pengurus/login");
	}

	public function password(){
		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		} else {
			$content 	= '_adminLayouts/password/edit';
			$data 		= array('session'     	=> SessionType::ADMIN,
								'csrfName'		=> $this->security->get_csrf_token_name(),
								'csrfToken'		=> $this->security->get_csrf_hash(),
								'appProfile'	=> true,
								'content'		=> $content);

			$this->load->view('_adminLayouts/wrapper', $data);
		}
	}

	public function passwordUpdate(){
		$input = $this->input->post(NULL, TRUE);

		switch ($this->session->userdata("admin_authStatus")){
			case AuthStatus::AUTHORIZED:

				$suffix = $input["suffix"];

				if ($suffix == "reset_password"){

					$getMemberPassword 	= $input["admin_password_retype"];
					$setMemberPassword	= array("admin_password" => password_hash($getMemberPassword, PASSWORD_BCRYPT));
					$whereMemberId		= array("id" => $this->session->userdata("admin_id"));

					$isPasswordUpdated = $this->CrudModel->ud("admin", $setMemberPassword, $whereMemberId);

					switch ($isPasswordUpdated){
						case "success":

							$reportDescription  = "Ganti password";

							$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

							$this->ActivityLog->actionLog($data);

							generalAllert("Update Password Berhasil!", "Password anda telah berhasil diubah.", AllertType::SUCCESS);
							break;
						case "failed":
							generalAllert("Update Password Gagal!", "Terjadi kesalahan saat mengganti password. Silahkan hubungi developer IT anda.", AllertType::FAILED);
							break;
						default:
							generalAllert("Update Password Gagal!", "Sistem mengalami gangguan saat mengganti password. Silahkan hubungi developer IT anda.", AllertType::FAILED);
					}

				} elseif ($suffix == "reset_transaction_code") {

					$getMemberPassword 	= $input["admin_transaction_code_retype"];
					$setMemberPassword	= array("admin_transaction_code" => password_hash($getMemberPassword, PASSWORD_BCRYPT));
					$whereMemberId		= array("id" => $this->session->userdata("admin_id"));

					$isPasswordUpdated = $this->CrudModel->ud("admin", $setMemberPassword, $whereMemberId);

					switch ($isPasswordUpdated){
						case "success":
							$reportDescription  = "Ganti kode transaksi";

							$data	= array("admin_id"				=> $this->session->userdata("admin_id"),
								"user_type"				=> CredentialType::ADMIN,
								"report_description"	=> $reportDescription,
								"created_at"			=> date('Y-m-d H:i:s'));

							$this->ActivityLog->actionLog($data);

							generalAllert("Update Kode Transaksi Berhasil!", "Kode transaksi anda telah berhasil diubah.", AllertType::SUCCESS);
							break;
						case "failed":
							generalAllert("Update Kode Transaksi Gagal!", "Terjadi kesalahan saat mengganti Kode transaksi. Silahkan hubungi developer IT anda.", AllertType::FAILED);
							break;
						default:
							generalAllert("Update Kode Transaksi Gagal!", "Sistem mengalami gangguan saat mengganti Kode transaksi. Silahkan hubungi developer IT anda.", AllertType::FAILED);
					}

				}

				$this->password();

				break;
			default:
				redirect('pengurus/login');
		};
	}

	function setNotificationOpened($id){

		if ($this->session->userdata('admin_authStatus') != AuthStatus::AUTHORIZED){
			redirect('pengurus/login');
		} else {
			$whereId 	= array("id" => $id);
			$dataStatus	= array("is_opened" => true);

			$dataNotif 	= current($this->CrudModel->gw("action_log", $whereId));
			$this->CrudModel->ud("action_log", $dataStatus, $whereId);

			redirect($dataNotif->reference_link);
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('pengurus/login');
	}

}
