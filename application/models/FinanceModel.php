<?php

class FinanceModel extends CrudModel {

	private function memberDeposit($id = NULL){

		if($id){
			$queryDeposit 	= 'SELECT member_deposit.id, member_deposit.deposit_member_id, member_deposit.deposit_ammount, member_deposit.deposit_last_code, member_deposit.deposit_status, 
							SUM(member_deposit.deposit_ammount) total_deposit 
							FROM member_deposit 
							WHERE deposit_status = "'.MemberDepositStatus::DEPOSIT_PROCESSED.'"
							AND deposit_member_id = "'.$id.'"';
		} else {
			$queryDeposit 	= 'SELECT member_deposit.id, member_deposit.deposit_member_id, member_deposit.deposit_ammount, member_deposit.deposit_last_code, member_deposit.deposit_status, 
							SUM(member_deposit.deposit_ammount) total_deposit 
							FROM member_deposit 
							WHERE deposit_status = "'.MemberDepositStatus::DEPOSIT_PROCESSED.'"';
		}

		return $this->q($queryDeposit);

	}

	private function memberPinjaman($id = NULL){

		if ($id){
			$queryPinjaman 	= 'SELECT member_pinjaman.id, member_pinjaman.member_id, member_pinjaman.pinjaman_member_ammount, member_pinjaman.approved_status, 
							SUM(member_pinjaman.pinjaman_member_ammount) total_pinjaman 
							FROM member_pinjaman 
							WHERE approved_status = "'.MemberPinjamanApproval::PINJAMAN_PROCESSED.'"
							AND member_id = "'.$id.'"';
		} else {
			$queryPinjaman 	= 'SELECT member_pinjaman.id, member_pinjaman.member_id, member_pinjaman.pinjaman_member_ammount, member_pinjaman.approved_status, 
							SUM(member_pinjaman.pinjaman_member_ammount) total_pinjaman 
							FROM member_pinjaman 
							WHERE approved_status = "'.MemberPinjamanApproval::PINJAMAN_PROCESSED.'"';
		}

		return $this->q($queryPinjaman);
	}

	private function tabunganImbalJasa($id = NULL){

		if($id){
			$queryTabunganImbalJasa = 'SELECT id, member_id, tij_accumulation, 
										SUM(tij_accumulation) total_tabungan_imbal_jasa 
										FROM tabungan_imbal_jasa 
										WHERE member_id = "'.$id.'"';

		} else{
			$queryTabunganImbalJasa = 'SELECT id, member_id, tij_accumulation, 
										SUM(tij_accumulation) total_tabungan_imbal_jasa 
										FROM tabungan_imbal_jasa';
		}

		return $this->q($queryTabunganImbalJasa);
	}

	private function simpananImbaJasa($id = NULL){

		if($id){
			$querySimpananImbalJasa = 'SELECT id, member_id, sij_ammount,
										SUM(sij_ammount) total_simpanan_imbal_jasa
										FROM simpanan_imbal_jasa
										WHERE sij_is_active IS NOT NULL AND member_id = "'.$id.'"';
		} else{
			$querySimpananImbalJasa = 'SELECT id, member_id, sij_ammount,
										SUM(sij_ammount) total_simpanan_imbal_jasa
										FROM simpanan_imbal_jasa
										WHERE sij_is_active IS NOT NULL ';
		}

		return $this->q($querySimpananImbalJasa);
	}

	private function tabunganMemberTfWallet($id= NULL){
		if ($id){
			$queryTabunganMemberTfToWallet = 'SELECT id, member_id, tabungan_tf_ammount,
											SUM(tabungan_tf_ammount) total_tabungan_transfer_wallet
											FROM tabungan_member_transfer
											WHERE tabungan_tf_approval = "'.MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED.'"
											AND tabungan_tf_type = "'.MemberTabunganTfType::TABUNGAN_TF_WALLET.'"
											AND member_id = "'.$id.'"';
		} else {
			$queryTabunganMemberTfToWallet = 'SELECT id, member_id, tabungan_tf_ammount,
											SUM(tabungan_tf_ammount) total_tabungan_transfer_wallet
											FROM tabungan_member_transfer
											WHERE tabungan_tf_approval = "'.MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED.'"
											AND tabungan_tf_type = "'.MemberTabunganTfType::TABUNGAN_TF_WALLET.'"';
		}

		return $this->q($queryTabunganMemberTfToWallet);
	}

	private function withdrawalMember($id = NULL){

		if ($id){
			$queryWd 	= 'SELECT withdrawal_member.id, withdrawal_member.wd_member_id, withdrawal_member.wd_ammount, withdrawal_member.wd_status, 
							SUM(withdrawal_member.wd_ammount) total_wd 
							FROM withdrawal_member 
							WHERE wd_status = "'.MemberWdStatus::WD_PROCESSED.'"
							AND wd_member_id = "'.$id.'"';
		} else{
			$queryWd 	= 'SELECT withdrawal_member.id, withdrawal_member.wd_member_id, withdrawal_member.wd_ammount, withdrawal_member.wd_status, 
							SUM(withdrawal_member.wd_ammount) total_wd 
							FROM withdrawal_member 
							WHERE wd_status = "'.MemberWdStatus::WD_PROCESSED.'"';
		}

		return $this->q($queryWd);
	}

	private function tabunganMember($id = NULL){

		if($id){
			$queryTabunganMember = 'SELECT id, member_id, tabungan_ammount,
									SUM(tabungan_ammount) total_tabungan_member
									FROM tabungan_member
									WHERE tabungan_approval = "'.MemberTabunganApproval::TABUNGAN_PROCESSED.'" 
								  	AND member_id = "'.$id.'"';
		} else{
			$queryTabunganMember = 'SELECT id, member_id, tabungan_ammount,
									SUM(tabungan_ammount) total_tabungan_member
									FROM tabungan_member
									WHERE tabungan_approval = "'.MemberTabunganApproval::TABUNGAN_PROCESSED.'"';
		}

		return $this->q($queryTabunganMember);
	}

	private function tabunganMemberTransfer($id = NULL){

		if ($id){
			$queryTabunganMemberTransfer = 'SELECT id, member_id, tabungan_tf_ammount,
											SUM(tabungan_tf_ammount) total_tabungan_transfer
											FROM tabungan_member_transfer
											WHERE tabungan_tf_approval = "'.MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED.'"
											AND member_id = "'.$id.'"';
		} else {
			$queryTabunganMemberTransfer = 'SELECT id, member_id, tabungan_tf_ammount,
											SUM(tabungan_tf_ammount) total_tabungan_transfer
											FROM tabungan_member_transfer
											WHERE tabungan_tf_approval = "'.MemberTabunganTfApproval::TABUNGAN_TF_PROCESSED.'"';
		}

		return $this->q($queryTabunganMemberTransfer);
	}

	private function memberLpointDebit($memberId = NULL){
		if ($memberId){
			$queryLpointDebit 	= 'SELECT *, SUM(MLP.lpoint_debit) total_lpoint_debit
									FROM member_lentera_point MLP
									WHERE member_id = "'.$memberId.'" AND lpoint_type = "'.MemberLpoinType::DEBIT.'"';
		} else {
			$queryLpointDebit 	= 'SELECT *, SUM(MLP.lpoint_debit) total_lpoint_debit 
									FROM member_lentera_point MLP 
									WHERE lpoint_type = "'.MemberLpoinType::DEBIT.'"';
		}

		return $this->q($queryLpointDebit);
	}

	private function memberLpointCredit($memberId = NULL){
		if ($memberId){
			$queryLpointDebit 	= 'SELECT *, SUM(MLP.lpoint_credit) total_lpoint_credit
									FROM member_lentera_point MLP
									WHERE member_id = "'.$memberId.'" AND lpoint_type = "'.MemberLpoinType::CREDIT.'" AND lpoint_credit_approval = "'.MemberLpointCreditApproval::LPOINT_CREDIT_APPROVED.'"';
		} else {
			$queryLpointDebit 	= 'SELECT *, SUM(MLP.lpoint_credit) total_lpoint_credit 
									FROM member_lentera_point MLP 
									WHERE lpoint_type = "'.MemberLpoinType::CREDIT.'" AND lpoint_credit_approval = "'.MemberLpointCreditApproval::LPOINT_CREDIT_APPROVED.'"';
		}

		return $this->q($queryLpointDebit);
	}

	/**
	 *
	 *	Getter
	 *
	 * */

	public function memberFinance($id = NULL){

		$result = array();

		$simpananAnggotaSummary = current($this->SimpananModel->getSimpananAnggotaSummary($id))->total_simpanan_anggota ?: 0;
		$simpananFundingSummary	= current($this->SimpananModel->getSimpananFundingSummary($id))->total_deposito ?: 0;

		/**
		 *
		 *	wallet debit
		 *
		 **/
		$memberDeposit 				= current($this->memberDeposit($id))->total_deposit ?: 0;
		$memberPinjaman 			= current($this->memberPinjaman($id))->total_pinjaman ?: 0;
		$memberTabunganImbalJasa	= current($this->tabunganImbalJasa($id))->total_tabungan_imbal_jasa ?: 0;
		$memberSimpananImbalJasa	= current($this->simpananImbaJasa($id))->total_simpanan_imbal_jasa ?: 0;
		$memberTfTabunganToWallet	= current($this->tabunganMemberTfWallet($id))->total_tabungan_transfer_wallet ?: 0;

		/**
		 *
		 *	wallet credit
		 *
		 **/
		$memberWithdrawal			= current($this->withdrawalMember($id))->total_wd ?: 0;
		$memberSimpanan				= $simpananAnggotaSummary + $simpananFundingSummary;
		$memberPinjamanDetail		= current($this->PinjamanModel->getMemberPinjamanDetailSummary(NULL,$id))->countPaidPinjaman;
		$memberPinjamanDetailLeft	= current($this->PinjamanModel->getMemberPinjamanDetailSummary(NULL, $id))->countUnpaidPinjaman;

		/**
		 *
		 *	tabungan debit
		 *
		 **/
		$memberTabungan	= current($this->tabunganMember($id))->total_tabungan_member ?: 0;

		/**
		 *
		 * tabungan credit
		 *
		 **/
		$tabunganMemberTransfer	= current($this->tabunganMemberTransfer($id))->total_tabungan_transfer ?: 0;

		/**
		 *
		 *	L-point debit
		 *
		 **/
		$memberLpointDebit	= current($this->memberLpointDebit($id))->total_lpoint_debit ?: 0;

		/**
		 *
		 * L-point credit
		 *
		 **/
		$memberLpointCredit	= current($this->memberLpointCredit($id))->total_lpoint_credit ?: 0;



		/**
		 *	Initiate result
		 *
		 *	Wallet Debit: memberDeposit, memberPinjaman, tabunganImbalJasa, simpananImbalJasa, tabunganMemberTransfer (to walllet), simpananMember (when tenor is expired);
		 * 	Wallet Credit: withdrawalMember, simpananMember, memberPinjamanDetail;
		 *	Tabungan Debit: tabunganMember
		 * 	Tabungan Credit: tabunganMemberTransfer
		 *
		 * */


		$walletBalance 	= ($memberDeposit + $memberPinjaman + $memberTabunganImbalJasa + $memberSimpananImbalJasa + $memberTfTabunganToWallet) - ($memberWithdrawal + $memberSimpanan + $memberPinjamanDetail);
		$tabunganBalance= $memberTabungan - $tabunganMemberTransfer;
		$lpointBalance	= $memberLpointDebit - $memberLpointCredit;
		$totalImbalJasa	= $memberTabunganImbalJasa + $memberSimpananImbalJasa;

		/**
		 * Wrapping all components
		 * */

		$dataBalance = new stdClass();

		$dataBalance->totalWalletBalance		= $walletBalance;
		$dataBalance->totalTabunganBalance		= $tabunganBalance;
		$dataBalance->totalLpointBalance		= $lpointBalance;

		$dataBalance->countDeposit				= $memberDeposit;
		$dataBalance->countWithdrawal			= $memberWithdrawal;

		$dataBalance->countPinjaman				= $memberPinjaman;
		$dataBalance->countSimpanan				= $memberSimpanan;
		$dataBalance->countTabungan				= $memberTabungan;
		$dataBalance->countPinjamanDetail		= $memberPinjamanDetail;
		$dataBalance->countPinjamanDetailLeft	= $memberPinjamanDetailLeft;

		$dataBalance->countTabunganImbalJasa	= $memberTabunganImbalJasa;
		$dataBalance->countSimpananImbalJasa	= $memberSimpananImbalJasa;
		$dataBalance->totalImbaJasa				= $totalImbalJasa;

		$dataBalance->totalSimpananAnggota		= $simpananAnggotaSummary;
		$dataBalance->totalDeposito				= $simpananFundingSummary;

		array_push($result, $dataBalance);

		return $result;
	}

}
