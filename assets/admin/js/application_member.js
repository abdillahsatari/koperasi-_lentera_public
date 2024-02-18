/*================================================================
						Plugin Init
=================================================================*/
$(document).ready(function() {
	(function ($) {
		/**
		/ DataTable
		 **/
		$('.default-table').DataTable({
			scrollX: true,
			responsive: true
		});

		$(".clean-table").DataTable({
			scrollX: true,
			responsive: true,
			bPaginate: false,
			bLengthChange: false,
			bFilter: false,
			bInfo: false,
			bAutoWidth: false});

		/**
		/ select2
		 **/
		$('.single-select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
		});

		$('.modal-select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
			dropdownParent: $("#exampleModal"),
		});

		/**
		/ CKEditor
		 **/
		if ($('#js-ckeditor').length > 0) {
			var _fileBrowser = $('#js-ckeditor').data("kcfinder");
			CKEDITOR.replace('js-ckeditor',{
				filebrowserImageBrowseUrl : _fileBrowser,
				height: '400px'});
		}
	})(jQuery);

});

/*================================================================
						jquery
=================================================================*/


(function($){

	/*================================================================
						Public Function
	=================================================================*/

	$.fn._getCsrfToken = function(_newToken) {

		if (_newToken) {
			$(this).find('input[name="_token"]').val(_newToken);
			return;
		} else {
			return $(this).find('input[name="_token"]').val();
		}
	};

	$('.js-currencyFormatter').each(function (){
		var _this 		= $(this);
		if (_this.length > 0){
			var dataValue	= _this.data("price");
			var decimal		= Number(dataValue).toFixed(0);
			var returnValue = decimal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			_this.append("<span>Rp. </span>"+returnValue);
		}
	});

	function copyToClipboard(_target) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(_target).select();
		document.execCommand("copy");
		$temp.remove();
		Lobibox.notify("success", {
			pauseDelayOnHover: true,
			continueDelayOnInactiveTab: false,
			position: 'top right',
			msg: 'Kode Referal Telah Disalin',
		});
	}

	var _target	 = $('.js-targeted_copy_value').val();
	var _btnCopy = $('.js-copy_btn');
	if(_btnCopy.length > 0 ){
		console.log("exist");
		_btnCopy.on("click", function () {
			console.log("btn copy clicked");
			copyToClipboard(_target);
		});
	}

	$.fn.modalClose = function(){
		// Reset the modal as default
		$('.modal').on('hidden.bs.modal', function(){
			var _form = $(this);

			_form.find('.invalid-feedback').each(function () {
				$(this).parent().find(".form-control").css("border-color", "");
				$(this).remove();
			});

			_form.find(".form-control").each(function () {
				$(this).val("");
			});

			/**
			 * TO DO
			 * Handle for select2 reset
			 * **/
			// _form.find('select.modal-select').each(function () {
			// 	$(this).select2('data', {}); // clear out values selected
			// 	$(this).select2({allowClear: true}); // re-init to show default status
			// });

		});
	};

	function imagePreview($form){
		$form.find('.js-image_upload').each(function () {
			var _this = $(this);
			_this.on("change", function () {
				var imageObj = window.URL.createObjectURL(_this[0].files[0]);
				var imgContainer = _this.parent().find(".image-preview");
				imgContainer.attr("src", imageObj);
			});
		});
	};

	function imageUpload($form){
		$form.find('.js-image_upload').each(function () {
			var _this 		= $(this);
			var _url 		= _this.data('url-upload');
			var _fileImage 	= _this[0].files[0];
			var formData 	= new FormData();
			formData.set('file', _fileImage);
			formData.set('_token', $form._getCsrfToken());

			if (_fileImage){
				$.ajax({
					url: _url,
					data: formData,
					type: "post",
					processData: false,
					contentType: false
				}).done(function(result) {
					var obj = $.parseJSON(result);

					if (obj.status == 'success') {
						_this.siblings('input').val(obj.data);
					}

					$form._getCsrfToken(obj.csrf_token);
				});
			}
		});
	}

	function validateForm($form) {
		$form.validate({
			onkeyup: false,
			onfocusout: false,
			ignore: '*:not([name])',
			errorClass: "invalid-feedback",
			errorElement: "p",
			highlight: function(element, errorClass) {
				$(element).removeClass(errorClass);
				$(element).css("border-color", "#dc3545");
			},
			unhighlight: function (element,errorClass) {
				$(element).css("border-color", "");
			},
			errorPlacement: function(error, element) {
				if (element.hasClass('js-input-with-plugin')){
					error.appendTo(element.parent("div").find(".js_input-error-placement"));
				} else if (element.hasClass('js-input-group')){
					error.appendTo(element.parent("div").parent("div").find(".js_input-error-placement"));
				} else {
					error.insertAfter(element);
				}
			},
		});

		$form.find('.js-form_action_btn').on('click', function (event) {
			event.preventDefault();
			var _this = $(this);

			var _imageUpload = $form.find('.js-image_upload');

			if (_imageUpload.length > 0) {
				var actionType = _this.html();
				var _placeholder = '';

				(actionType == 'Submit') ? _placeholder = 'Submitting...' : _placeholder = 'Updating...';
				_this.attr('disabled', true);
				_this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> '+ _placeholder);

				imageUpload($form);

				setTimeout(function(){
					_this.attr('disabled', false);
					_this.html((actionType == 'Submit') ? 'Submit' : 'Update');
					if ($form.valid()){
						$form.submit();
					}
				}, 3000);

			} else {
				if ($form.valid()) {
					$form.submit();
				}
			}
		});
	};

	/*================================================================
						Page Function
	=================================================================*/

	/**
	 * / Member Deposit Page
	 **/
	var _formMemberDeposit = $('.js_form-member-deposit');

	if (_formMemberDeposit.length > 0) {
		var _inputPaymentGateway	= $('input[name="deposit_lentera_bank_id"]');
		var _inputAmmount			= $('input[name="deposit_ammount"]');

		validateForm(_formMemberDeposit);

		_inputPaymentGateway.rules('add', {
			required : true,
			messages: {
				required: "Payment Gateway Tidak Boleh Kosong",
			}
		});

		_inputAmmount.rules('add', {
			required : true,
			messages: {
				required: "Nominal Tidak Boleh Kosong"
			}
		});
	}

	var _showMemberDepositDetail = $('.js_member-deposit-detail');

	if (_showMemberDepositDetail.length > 0) {
		$('#detailDepositModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataDepositId	= $(e.relatedTarget).data('deposit-id');
			var formData 		= new FormData();
			formData.set('dataDepositId', _dataDepositId);
			formData.set('_token', _formMemberDeposit._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 1000
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {

					var _depositStatus	= obj.data[0]['deposit_status'];
					var _depositAmmount	= Number(obj.data[0]['deposit_ammount']) + Number(obj.data[0]['deposit_last_code']);
					var _ammountWithFormat	= _depositAmmount.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					var _badgeType;

					switch (_depositStatus) {
						case "Deposit Baru":
							_badgeType = "badge-info";
							break;
						case "Deposit Diproses":
							_badgeType = "badge-primary";
							break;
						case "Deposit Pending":
							_badgeType = "badge-light";
							break;
						case "Deposit Dibatalkan":
							_badgeType = "badge-danger";
							break;
						default:
							_badgeType = "badge-dark";
							break;
					}

					_this.find('#js_deposit-status-badge').attr("class", "badge " + _badgeType);
					_this.find('#js_deposit-status-badge').html(_depositStatus == 'Deposit Baru' ? "Menunggu Konfirmasi" : _depositStatus);
					_this.find('.js_deposit-code').html(obj.data[0]['deposit_code']);
					_this.find('.js_deposit-gateway').html(obj.data[0]['nama_bank']);
					_this.find('.js_deposit-gateway-number').html(obj.data[0]['nomor_rekening']);
					_this.find('.js_deposit-gateway-account').html(obj.data[0]['nama_pemilik_account']);
					_this.find('.js_deposit-ammount').html('<span> Rp. '+_ammountWithFormat+'</span> ');
					_this.find('.js_gateway-full-info').html(obj.data[0]['nama_bank'] + ' - ' + obj.data[0]['nomor_rekening'] + ' a.n. ' + obj.data[0]['nama_pemilik_account']);
					_this.find('.js_last-code').html(obj.data[0]['deposit_last_code']);
					_this.find('.js_member-uid').html(obj.data[0]['member_referal_code']);
				}

				_formMemberDeposit._getCsrfToken(obj.csrf_token);
			});

		});
	}

	/**
	 *
	 * 	Member Withdrawal Page
	 *
	 * **/
	var _formMemberWithdrawal = $('.js_form-member-withdrawal');

	if (_formMemberWithdrawal.length > 0){
		var _inputAmmount			= $('input[name="wd_ammount"]');
		var _inputTransactionCode	= $('input[name="member_transaction_code"]');
		var _balanceIsValidated		= true;

		_inputAmmount.on('change', function () {
			var _this			= $(this);
			var _url 			= _formMemberWithdrawal.data("url");
			var _inputValue		= _this.val();
			var formData 		= new FormData();
			formData.set('dataInputAmmount', _inputValue);
			formData.set('_token', _formMemberWithdrawal._getCsrfToken());

			$.ajax({
				url: _url,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);
				_balanceIsValidated = obj.data;
				_formMemberWithdrawal._getCsrfToken(obj.csrf_token);
			});
		});

		validateForm(_formMemberWithdrawal);

		_inputAmmount.rules('add', {
			required : true,
			balanceIsValidated : true,
			messages: {
				required: "Nominal Wd Tidak Boleh Kosong",
				balanceIsValidated: "Saldo dompet tidak cukup"
			}
		});

		_inputTransactionCode.rules('add', {
			required : true,
			messages: {
				required: "Kode Transaksi Tidak Boleh Kosong"
			}
		});

		$.validator.addMethod("balanceIsValidated", function() {
			if (!_balanceIsValidated){
				return false;
			} else {
				return true;
			};
		});
	}

	/**
	 * / Member Keanggotaan Page
	 **/
	var _formMemberRegisterTeam = $('.js_form-member-register-team');

	if (_formMemberRegisterTeam.length > 0) {
		var _inputName	= $('input[name="member_full_name"]');
		var _inputEmail	= $('input[name="member_email"]');
		var _inputPhone	= $('input[name="member_phone_number"]');
		var _inputKtp	= $('input[name="member_ktp_number"]');
		var _phoneNumberIsValid = true;

		_inputPhone.on('change', function () {
			var _this			= $(this);
			var _url 			= _formMemberRegisterTeam.data("url");
			var _inputValue		= _this.val();
			var formData 		= new FormData();
			formData.set('dataMemberPhoneNumber', _inputValue);
			formData.set('_token', _formMemberRegisterTeam._getCsrfToken());

			$.ajax({
				url: _url,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);
				(obj.data > 0) ? _phoneNumberIsValid = false : _phoneNumberIsValid = true ;
				_formMemberRegisterTeam._getCsrfToken(obj.csrf_token);
			});
		});

		validateForm(_formMemberRegisterTeam);

		_inputName.rules('add', {
			required : true,
			messages: {
				required: "Nama Lengkap Tidak Boleh Kosong"
			}
		});

		_inputEmail.rules('add', {
			required : true,
			messages: {
				required: "Email Tidak Boleh Kosong",
				email: "Format Email Tidak Sesuai"
			}
		});

		_inputPhone.rules('add', {
			required : true,
			phoneNumberValidate : true,
			messages: {
				required: "No. Hp Tidak Boleh Kosong",
				phoneNumberValidate: "No. Hp Sudah Terdaftar"
			}
		});

		_inputKtp.rules('add', {
			required : true,
			messages: {
				required: "No. Ktp Tidak Boleh Kosong"
			}
		});

		$.validator.addMethod("phoneNumberValidate", function() {
			if (!_phoneNumberIsValid){
				return false;
			} else {
				return true;
			};
		});
	}

	var _showMemberTimDetail = $('.js_member-tim-detail');

	if (_showMemberTimDetail.length > 0){

		$('#showMemberTimDetail').on('show.bs.modal', function(){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataMemberId	= _this.data('member-id');
			var formData 		= new FormData();
			formData.set('dataMemberId', _dataMemberId);
			formData.set('_token', _showMemberTimDetail._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 1000
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {
					_this.find('.js_member-full-name').html(obj.data[0]['member_full_name']);
					_this.find('.js_member-referal-code').html(obj.data[0]['member_referal_code']);
					_this.find('.js_member-verified').html(obj.data[0]['member_is_verified'] ? "Email Telah Diverifikasi" : "Belum Verifikasi Email");
					_this.find('.js_member-kyc').html(obj.data[0]['member_is_kyc'] ? "Selesai" : "Belum Selesai");
					_this.find('.js_member-simpanan-pokok').html(obj.data[0]['member_is_simpo'] ? "Selesai" : "Belum Selesai");
					_this.find('.js_member-simpanan-wajib').html(obj.data[0]['member_is_simwa'] ? "Selesai" : "Belum Selesai");
				}

				_showMemberTimDetail._getCsrfToken(obj.csrf_token);
			});

		});
	}

	/**
	 * / Member Simpanan Page
	 **/

	/* Simpanan Funding */
	var _formMemberPostSimpananFunding = $('.js_member-post-simpanan-funding');

	if (_formMemberPostSimpananFunding.length > 0){
		$('#simpananFunding').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataSimpananId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataSimpananId', _dataSimpananId);
			formData.set('_token', _formMemberPostSimpananFunding._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 1000
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {

					var _simpananMinimumPrice		= Number(obj.data[0]['simpanan_minimum_price']);
					var _simpananMinimumPriceFormat	= _simpananMinimumPrice.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_formMemberPostSimpananFunding.find('input[name=lentera_simpanan_content_id]').val(obj.data[0]['id']);
					_formMemberPostSimpananFunding.find('input[name=lentera_simpanan_content_name]').val(obj.data[0]['simpanan_name']);

					_this.find('.js_simpanan-name').html(obj.data[0]['simpanan_name']);
					_this.find('.js_simpanan-price').html('<span> Rp. '+_simpananMinimumPriceFormat+'</span> ');
					_this.find('.js_simpanan-bagi-untung').html(obj.data[0]['simpanan_balas_jasa_percentage'] + '%');
					_this.find('.js_simpanan-duration').html(obj.data[0]['simpanan_duration']);

					_this.find('.js-simpanan_member_input_ammount').html(`<label>Nominal Pembayaran</label>
																			<input type="number" class="form-control" id="simpanan_member_with_ammount" value="" placeholder="Nominal Simpanan">
																			<small class="text-danger"></small>`);

					_formMemberPostSimpananFunding.find('input[name=simpanan_input_type]').val("ammount");

					_this.find('#simpanan_member_with_ammount').on("change keyup", function () {
						var _thisInputVal = $(this).val();
						_formMemberPostSimpananFunding.find('input[name="simpanan_member_ammount"]').val(_thisInputVal);
					});

				}

				_formMemberPostSimpananFunding._getCsrfToken(obj.csrf_token);
			});
		});

		_formMemberPostSimpananFunding.modalClose();
		$('#simpananFunding').on('hidden.bs.modal', function () {
			var _this = $(this);
			_this.find('.js-simpanan_member_input_ammount').html('');
		});

	}


	/* Simpanan Anggota */

	var _formMemberPostSimpananAnggota = $('.js_member-post-simpanan-anggota');

	var _showMemberSimpananAnggota = $('.js_member-post-simpanan-anggota');

	if (_showMemberSimpananAnggota.length > 0){

		$('#exampleModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataSimpananId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataSimpananId', _dataSimpananId);
			formData.set('_token', _formMemberPostSimpananAnggota._getCsrfToken());
			_formMemberPostSimpananAnggota.find('input[name="simpanan_id"]').val(_dataSimpananId);

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 500
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {
					var _simpananMinimumPrice		= Number(obj.data[0]['simpanan_minimum_price']);
					var _simpananMinimumPriceFormat	= _simpananMinimumPrice.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

					_formMemberPostSimpananAnggota.find('input[name=lentera_simpanan_content_id]').val(obj.data[0]['id']);
					_formMemberPostSimpananAnggota.find('input[name=lentera_simpanan_content_name]').val(obj.data[0]['simpanan_name']);

					if (obj.data[0]['simpanan_name'] == "Simpanan Sukarela") {

						_this.find('.js_detail-simpanan_anggota').html(obj.data[0]['simpanan_name']+ ' tidak memiliki patokan minimum ataupun maksimum biaya simpanan');

						_this.find('.js-simpanan_member_input_ammount').html('<label>Nominal Pembayaran</label>\n' +
							'\t\t\t\t\t\t\t\t<input type="number" class="form-control" id="simpanan_member_with_ammount" value="" placeholder="Nominal Simpanan">\n' +
							'\t\t\t\t\t\t\t\t<small class="text-danger"></small>');

						_formMemberPostSimpananAnggota.find('input[name=simpanan_input_type]').val("ammount");

						_this.find('#simpanan_member_with_ammount').on("change keyup", function () {
							var _thisInputVal = $(this).val();
							_formMemberPostSimpananAnggota.find('input[name="simpanan_member_ammount"]').val(_thisInputVal);
						});


					} else {
						_this.find('.js_detail-simpanan_anggota').html('Biaya ' + obj.data[0]['simpanan_name']+ ' adalah ' + '<span> Rp. '+_simpananMinimumPriceFormat+'</span>');
						_formMemberPostSimpananAnggota.find('input[name=simpanan_input_type]').val("pokok");

						if (obj.data[0]['simpanan_name'] == "Simpanan Wajib"){
							_this.find('.js-simpanan_member_input_ammount').html('<div class="input-group">\n' +
								'\t\t\t<input type="number" class="form-control" id="simpanan_member_month"\n' +
								'\t\t\t\t   placeholder="1" aria-describedby="simpanan_member_month">\n' +
								'\t\t\t<div class="input-group-append">\n' +
								'\t\t\t\t<span class="input-group-text btn btn-primary" id="inputGroupPrepend">\n' +
								'\t\t\t\t\t<div class="col-sm-1">\n' +
								'\t\t\t\t\t\t Bulan \n' +
								'\t\t\t\t\t</div>\n' +
								'\t\t\t\t</span>\n' +
								'\t\t\t</div>\n' +
								'\t\t</div>');

							_formMemberPostSimpananAnggota.find('input[name=simpanan_input_type]').val("month");

							_this.find('#simpanan_member_month').on("change keyup", function () {
								var _thisInputVal = $(this).val();
								_formMemberPostSimpananAnggota.find('input[name="simpanan_member_month"]').val(_thisInputVal);
							});
						}
					}
				}

				_formMemberPostSimpananAnggota._getCsrfToken(obj.csrf_token);
			});
		});

		_showMemberSimpananAnggota.modalClose();
		$('#exampleModal').on('hidden.bs.modal', function () {
			var _this = $(this);
			_this.find('.js-simpanan_member_input_ammount').html('');
		});
	}


	/**
	 * / Member Pinjaman Page
	 **/

	var _showMemberPostPinjaman = $('.js_member-post-pinjaman');

	if (_showMemberPostPinjaman.length > 0){
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataPinjamanId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataPinjamanContentId', _dataPinjamanId);
			formData.set('_token', _showMemberPostPinjaman._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 500
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);
				var _dataPinjaman = obj.data;

				if (obj.status == 'success') {

					var _tenorType = _dataPinjaman["pinjaman_content_tenor_type"].slice(0, -2);

					jQuery.each(_dataPinjaman["pinjaman_setting"], function( _key, _value){
						$("#pinjamanSelectTenor").append(`<option value="`+_value["pinjaman_content_tenor"]+`" data-id="`+_value["id"]+`" >`+_value["pinjaman_content_tenor"]+" "+_tenorType+`</option>`);
					});

					var _selectedTenor 		= $("#pinjamanSelectTenor").find(':selected');
					var _contentDetailId 	= _selectedTenor.data("id");
					_showMemberPostPinjaman.find('input[name="lentera_pinjaman_content_id"]').val(_dataPinjaman["pinjaman_id"]);
					_showMemberPostPinjaman.find('input[name="pinjaman_content_detail_id"]').val(_contentDetailId);

				}

				_showMemberPostPinjaman._getCsrfToken(obj.csrf_token);
			});

			_showMemberPostPinjaman.find('#pinjamanSelectTenor').on('change', function () {
				var _contentDetailId 	= $(this).find(':selected').data("id");
				_showMemberPostPinjaman.find('input[name="pinjaman_content_detail_id"]').val(_contentDetailId);
			});

		});

		_showMemberPostPinjaman.modalClose();
		$('#exampleModal').on('hidden.bs.modal', function () {
			var _this = $(this);
			_this.find('.js-simpanan_member_input_ammount').html('');
			$("#pinjamanSelectTenor").html("");
		});
	}

	/**
	 *
	 *  Member Pinjaman Paid Page
	 *
	 * */

	var _formMemberPostPinjamanDetail = $('.js_member-post-pinjaman-detail');

	if (_formMemberPostPinjamanDetail.length > 0){
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this					= $(this);
			var _dataUrl 				= _this.data('url');
			var _dataPinjamanDetailId	= $(e.relatedTarget).data('id');
			var formData 				= new FormData();
			formData.set('dataMemberPinjamanDetailId', _dataPinjamanDetailId);
			formData.set('_token', _formMemberPostPinjamanDetail._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 500
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);
				var _dataPinjaman = obj.data;

				if (obj.status == 'success') {
					var _pinjamanBungaAmmount = Number(_dataPinjaman['pinjaman_bunga_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_this.find('.js_pinjaman-ref-number').html(_dataPinjaman['pinjaman_detail_transaction_code']);
					_this.find('.js_pinjaman-paid-number').html(_dataPinjaman['pinjaman_detail_payment_code']);
					_this.find('.js_pinjaman-tenor-number').html(_dataPinjaman['pinjaman_tenor_number']);
					_this.find('.js_pinjaman-due-date').html(_dataPinjaman['pinjaman_due_date']);
					_this.find('.js_pinjaman-bunga-ammount').html('<span> Rp. '+ _pinjamanBungaAmmount +'</span> ');

					_this.find('input[name="pinjaman_detail_id"]').val(_dataPinjaman["id"]);
					_this.find('input[name="member_id"]').val(_dataPinjaman["member_id"]);
					_this.find('input[name="pinjaman_id"]').val(_dataPinjaman["pinjaman_id"]);
					_this.find('input[name="pinjaman_tenor_number"]').val(_dataPinjaman["pinjaman_tenor_number"]);
					_this.find('input[name="pinjaman_bunga_ammount"]').val(_dataPinjaman["pinjaman_bunga_ammount"]);

				}

				_formMemberPostPinjamanDetail._getCsrfToken(obj.csrf_token);
			});
		});
	}


	/**
	 *
	 *  Member Tabungan Page
	 *
	 * */
	var _formMemberPostTabungan = $('.js_member-post-tabungan');

	if (_formMemberPostTabungan.length > 0){
		var _inputTabunganAmmount	= $('input[name="tabungan_ammount"]');
		var _inputBankId			= $('input[name="lentera_bank_id"]');

		validateForm(_formMemberPostTabungan);

		_inputTabunganAmmount.rules('add', {
			required : true,
			messages: {
				required: "Nominal Tabungan Tidak Boleh Kosong",
			}
		});
		_inputBankId.rules('add', {
			required : true,
			messages: {
				required: "Rekening Tabungan Tidak Boleh Kosong",
			}
		});
	}

	var _showMemberTabunganDetail = $('.js_member-tabungan-detail');

	if (_showMemberTabunganDetail.length > 0) {
		$('#detailTabunganModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataTabunganId	= $(e.relatedTarget).data('tabungan-id');
			var formData 		= new FormData();
			formData.set('dataTabunganId', _dataTabunganId);
			formData.set('_token', _showMemberTabunganDetail._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 1000
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {

					var _tabunganAproval	= obj.data['tabungan_approval'];
					var _ammountWithFormat	= Number(obj.data['tabungan_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					var _badgeType;

					switch (_tabunganAproval) {
						case "Tabungan Baru":
							_badgeType = "badge-info";
							break;
						case "Tabungan Diproses":
							_badgeType = "badge-primary";
							break;
						case "Tabungan Pending":
							_badgeType = "badge-light";
							break;
						case "Tabungan Dibatalkan":
							_badgeType = "badge-light";
							break;
						case "Tabungan Ditolak":
							_badgeType = "badge-danger";
							break;
						default:
							_badgeType = "badge-dark";
							break;
					}

					_this.find('#js-tabungan-status-badge').attr("class", "badge " + _badgeType);
					_this.find('#js-tabungan-status-badge').html(_tabunganAproval == 'Tabungan Baru' ? "Menunggu Konfirmasi" : _tabunganAproval);
					_this.find('.js_tabungan-code').html(obj.data['tabungan_code']);
					_this.find('.js_tabungan-gateway').html(obj.data['nama_bank']);
					_this.find('.js_tabungan-gateway-number').html(obj.data['nomor_rekening']);
					_this.find('.js_tabungan-gateway-account').html(obj.data['nama_pemilik_account']);
					_this.find('.js_tabungan-ammount').html('<span> Rp. '+_ammountWithFormat+'</span> ');

					if (_tabunganAproval == "Tabungan Baru"){
						$('.js_tabungan-instructions').html(`<span>Mohon melakukan transfer ke <strong>`+ obj.data['nama_bank'] + ` - ` + obj.data['nomor_rekening'] + ` - ` + obj.data['nama_pemilik_account'] + `</strong></span><br/>
															<span>Total tabungan Anda yaitu Rp. <strong>` + _ammountWithFormat + `</strong>.</span><br/>
															<span>Mohon masukkan kode tabungan <strong>[ ` + obj.data['tabungan_code'] + ` ]</strong> dan No. Anggota <strong>[ ` + obj.data['member_referal_code'] + ` ]</strong> pada deskripsi.</span><br/>
															<span>Apabila tabungan belum di verifikasi dalam 1x24 jam (jam kerja), silahkan hubungi kami via email <strong>cs@lenteradigitalindonesia.com</strong> atau via Whatsapp <strong>+6285732598556</strong>.</span>`);
					}
				}

				_showMemberTabunganDetail._getCsrfToken(obj.csrf_token);
			});

		});
	}

	/**
	 *
	 *  Member Online Shop Page
	 *
	 * */
	var _showLenteraOlshopProductDetail = $('.js_member-show-checkout-product');

	if (_showLenteraOlshopProductDetail.length > 0) {
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataProductId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataLenteraOlshopProductId', _dataProductId);
			formData.set('_token', _showLenteraOlshopProductDetail._getCsrfToken());

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {

					_this.find('input[name="lentera_olshop_product_id"]').val(obj.data['id']);
					_this.find('input[name="lentera_olshop_product_price"]').val(obj.data['olshop_product_price']);
				}

				_showLenteraOlshopProductDetail._getCsrfToken(obj.csrf_token);
			});
		});

		_showLenteraOlshopProductDetail.modalClose();
		$('#exampleModal').on('hidden.bs.modal', function () {
			var _this = $(this);
			_this.find('.form-control').val('');
		});
	}


	/**
	 *
	 *  Member Tabungan Transfer Page
	 *
	 * */
	var _formMemberPostTabunganTf = $('.js_member-post-tabungan-transfer');

	if (_formMemberPostTabunganTf.length > 0){
		var _inputTabunganTfAmmount	= $('input[name="tabungan_tf_ammount"]');
		var _inputTfType			= $('input[name="tabungan_tf_type"]');

		validateForm(_formMemberPostTabunganTf);

		_inputTabunganTfAmmount.rules('add', {
			required : true,
			messages: {
				required: "Nominal Transfer Tabungan Tidak Boleh Kosong",
			}
		});
		_inputTfType.rules('add', {
			required : true,
			messages: {
				required: "Jenis Transfer Tidak Boleh Kosong",
			}
		});
	}

	var _showMemberTabunganTfDetail = $('.js_member-tabungan-transfer-detail');

	if (_showMemberTabunganTfDetail.length > 0) {
		$('#detailTabunganTfModal').on('show.bs.modal', function(e){
			var _this				= $(this);
			var _dataUrl 			= _this.data('url');
			var _dataTabunganTfId	= $(e.relatedTarget).data('tabungan-id');
			var _dataTabunganMbaId	= $(e.relatedTarget).data('mba-id');
			var formData 			= new FormData();
			formData.set('tabunganTfMemberId', _dataTabunganTfId);
			formData.set('tabunganTfMemberBankId', _dataTabunganMbaId);
			formData.set('_token', _showMemberTabunganTfDetail._getCsrfToken());

			$('#block-modal').block({
				message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
				timeout: 1000
			});

			$.ajax({
				url: _dataUrl,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);

				if (obj.status == 'success') {

					var _tabunganTfAproval	= obj.data['tabungan_tf_approval'];
					var _ammountWithFormat	= Number(obj.data['tabungan_tf_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					var _badgeType;

					switch (_tabunganTfAproval) {
						case "Transfer Tabungan Baru":
							_badgeType = "badge-info";
							break;
						case "Transfer Tabungan Diproses":
							_badgeType = "badge-primary";
							break;
						case "Transfer Tabungan Pending":
							_badgeType = "badge-info";
							break;
						case "Transfer Tabungan Dibatalkan":
							_badgeType = "badge-dark";
							break;
						case "Transfer Tabungan Ditolak":
							_badgeType = "badge-danger";
							break;
						default:
							_badgeType = "badge-light";
							break;
					}

					_this.find('#js-tabungan-tf-status-badge').attr("class", "badge " + _badgeType);
					_this.find('#js-tabungan-tf-status-badge').html(_tabunganTfAproval == 'Transfer Tabungan Baru' ? "Menunggu Konfirmasi" : _tabunganTfAproval);
					_this.find('.js_tabungan-transfer-code').html(obj.data['tabungan_tf_tr_code']);
					_this.find('.js_tabungan-transfer-type').html(obj.data['tabungan_tf_type']);
					_this.find('.js_tabungan-transfer-ammount').html('<span> Rp. '+_ammountWithFormat+'</span> ');

					if (obj.data['tabungan_tf_type'] == "Withdrawal"){
						$('.js_tabungan-transfer-gateway-info').html(`
						<table class="table table-striped table-bordered table-condensed">
							<tbody>
								<tr>
									<td>Gateway</td>
									<td>`+ obj.data['bank_account_name'] +`</td>
								</tr>
								<tr>
									<td>Nomor Rekening</td>
									<td>`+ obj.data['bank_account_number'] +`</td>
								</tr>
								<tr>
									<td>Nama Pemilik Akun</td>
									<td>`+ obj.data['bank_account_owner'] +`</td>
								</tr>
							</tbody>
						</table>`);
					}
				}
				_showMemberTabunganTfDetail._getCsrfToken(obj.csrf_token);
			});
		});

		_showMemberTabunganTfDetail.modalClose();
		$('#detailTabunganTfModal').on('hidden.bs.modal', function () {
			var _this = $(this);
			_this.find('.js_tabungan-transfer-gateway-info').remove();
		});
	}


	/**
	 * / Member Profile Edit Page
	 **/
	var _formEditProfile = $('.js_member-profile-edit');

	if (_formEditProfile.length > 0){
		var getAppProfileBody 	= $('.app-profile');
		var _inputEmail			= $('input[name="member_email"]');
		var _inputPhone			= $('input[name="member_phone_number"]');
		var _inputKtp			= $('input[name="member_ktp_number"]');
		var _inputKtpImg		= $('input[name="member_ktp_image"]');
		var _inputName			= $('input[name="member_full_name"]');
		var _inputBirthPlace	= $('input[name="member_birth_place"]');
		var _inputBirthDate		= $('input[name="member_birth_date"]');
		var _inputLastEducation	= $('input[name="member_last_education"]');
		var _inputJob			= $('input[name="member_job"]');
		var _inputAddress		= $('input[name="member_address"]');
		var _inputKelurahan		= $('input[name="member_kelurahan"]');
		var _inputKecamatan		= $('input[name="member_kecamatan"]');
		var _inputKota			= $('input[name="member_kota"]');
		var _inputPrvinsi		= $('input[name="member_provinsi"]');
		var _inputKodePos		= $('input[name="member_kode_pos"]');

		imagePreview(_formEditProfile);
		validateForm(_formEditProfile);

		_inputEmail.rules('add', {
			required : true,
			messages: {
				required: "Email Tidak Boleh Kosong",
				email: "Format Email Tidak Sesuai"
			}
		});
		_inputPhone.rules('add', {
			required : true,
			messages: {
				required: "No. Hp Tidak Boleh Kosong"
			}
		});
		_inputKtp.rules('add', {
			required : true,
			messages: {
				required: "Nomor KTP Tidak Boleh Kosong"
			}
		});
		_inputKtpImg.rules('add', {
			required : true,
			messages: {
				required: "Gambar KTP Tidak Boleh Kosong"
			}
		});
		_inputName.rules('add', {
			required : true,
			messages: {
				required: "Nama Lengkap Tidak Boleh Kosong"
			}
		});
		_inputBirthPlace.rules('add', {
			required : true,
			messages: {
				required: "Tempat Lahir Tidak Boleh Kosong"
			}
		});
		_inputBirthDate.rules('add', {
			required : true,
			messages: {
				required: "Tgl Lahir Tidak Boleh Kosong"
			}
		});
		_inputLastEducation.rules('add', {
			required : true,
			messages: {
				required: "Pendidikan Terakhir Tidak Boleh Kosong"
			}
		});
		_inputJob.rules('add', {
			required : true,
			messages: {
				required: "Pekerjaan Tidak Boleh Kosong"
			}
		});
		_inputAddress.rules('add', {
			required : true,
			messages: {
				required: "Alamat Tidak Boleh Kosong"
			}
		});
		_inputKelurahan.rules('add', {
			required : true,
			messages: {
				required: "Kelurahan Tidak Boleh Kosong"
			}
		});
		_inputKecamatan.rules('add', {
			required : true,
			messages: {
				required: "Kecamatan Tidak Boleh Kosong"
			}
		});
		_inputKota.rules('add', {
			required : true,
			messages: {
				required: "Kota Tidak Boleh Kosong"
			}
		});
		_inputPrvinsi.rules('add', {
			required : true,
			messages: {
				required: "Provinsi Tidak Boleh Kosong"
			}
		});
		_inputKodePos.rules('add', {
			required : true,
			messages: {
				required: "Kode Pos Tidak Boleh Kosong"
			}
		});
	}

	/*
	* 	Member Profile Edit Password Page
	* */
	var _formMemberEditPassword = $('.js_member-profile-password-edit');

	if (_formMemberEditPassword.length > 0){
		var _inputPassword			= $('input[name="member_password"]');
		var _inputPasswordRetype	= $('input[name="member_password_retype"]');

		validateForm(_formMemberEditPassword);

		_inputPassword.rules('add', {
			required : true,
			minlength: 5,
			messages: {
				required: "Password Tidak Boleh Kosong",
				minLength: "Password disarankan setidaknya 5 karakter"
			}
		});

		_inputPasswordRetype.rules('add', {
			required : true,
			equalTo: "#member_password",
			messages: {
				required: "Konfirmasi Password Tidak Boleh Kosong",
				equalTo: "Konfirmasi Password Tidak Sama"
			}
		});
	}

	/*
	* 	Member Profile Edit Transaction Code Page
	* */
	var _formMemberEditTransactionCode = $('.js_member-profile-transaction-code-edit');

	if (_formMemberEditTransactionCode.length > 0){
		var _inputTransactionCode		= $('input[name="member_transaction_code"]');
		var _inputTransactionCodeRetype	= $('input[name="member_transaction_code_retype"]');

		validateForm(_formMemberEditTransactionCode);

		_inputTransactionCode.rules('add', {
			required : true,
			minlength: 5,
			messages: {
				required: "Kode transaksi Tidak Boleh Kosong",
				minLength: "Kode transaksi disarankan setidaknya 5 karakter"
			}
		});

		_inputTransactionCodeRetype.rules('add', {
			required : true,
			equalTo: "#member_transaction_code",
			messages: {
				required: "Konfirmasi kode transaksi Tidak Boleh Kosong",
				equalTo: "Konfirmasi kode transaksi Tidak Sama"
			}
		});
	}

	/*
	* 	Member Profile Edit Bank Account Page
	* */
	var _formMemberEditBankAccountInfo = $('.js_member-profile-bank-account-edit');

	if (_formMemberEditBankAccountInfo.length > 0){
		var _inputAccountName	= $('input[name="bank_account_name"]');
		var _inputAccountNumber	= $('input[name="bank_account_number"]');
		var _inputAccountOwner	= $('input[name="bank_account_owner"]');

		validateForm(_formMemberEditBankAccountInfo);

		_inputAccountName.rules('add', {
			required : true,
			messages: {
				required: "Nama Bank Tidak Boleh Kosong",
			}
		});
		_inputAccountNumber.rules('add', {
			required : true,
			messages: {
				required: "Nomor Rekening Tidak Boleh Kosong"
			}
		});
		_inputAccountOwner.rules('add', {
			required : true,
			messages: {
				required: "Nama Pemilik Akun Bank Tidak Boleh Kosong"
			}
		});
	}

})(jQuery);
