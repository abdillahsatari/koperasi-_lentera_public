/*================================================================
						Plugin Init
=================================================================*/
$(document).ready(function() {
	(function ($) {
		/**
		/ DataTable
		 **/
		$(".default-table").DataTable({
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
		$(".single-select").select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
		});

		$(".modal-select").select2({
			theme: "bootstrap4",
			width: $(this).data("width") ? $(this).data("width") : $(this).hasClass("w-100") ? "100%" : "style",
			placeholder: $(this).data("placeholder"),
			allowClear: Boolean($(this).data("allow-clear")),
			dropdownParent: $("#exampleModal"),
		});

		/**
		/ CKEditor
		 **/
		if ($('#js-ckeditor').length > 0) {
			var _fileBrowser = $('#js-ckeditor').data("kcfinder");
			CKEDITOR.replace('js-ckeditor',{
				filebrowserImageBrowseUrl : _fileBrowser,
				height: '200px'});
		}

		if ($('#js-ckeditor-minimal-toolbar').length > 0) {
			CKEDITOR.replace('js-ckeditor-minimal-toolbar',{
				height: '200px',
				toolbar: [
					['Bold', 'Italic', 'Underline', 'Strike', 'TextColor', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
				]});
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

	function chartInit(_dataSimpanan, _dataPinjaman){
		var options3 = {
			chart: {
				height: 350,
				type: 'bar',
			},
			plotOptions: {
				bar: {
					horizontal: false,
					columnWidth: '55%',
					endingShape: 'rounded'
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				show: true,
				width: 2,
				colors: ['transparent']
			},
			series: [{
				name: 'Simpanan',
				// data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
				data: _dataSimpanan
			}, {
				name: 'Pinjaman',
				// data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
				data: _dataPinjaman
			}],
			xaxis: {
				categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
				labels: {
					style: {
						colors: 'rgba(94, 96, 110, .5)'
					}
				}
			},
			yaxis: {
				title: {
					text: 'Rp. (Rupiah)'
				}
			},
			fill: {
				opacity: 1

			},
			tooltip: {
				y: {
					formatter: function (val) {
						return "Rp. " + Number(val).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					}
				}
			},
			grid: {
				borderColor: 'rgba(94, 96, 110, .5)',
				strokeDashArray: 4
			}
		}

		var chart3 = new ApexCharts(
			document.querySelector("#js_admin-dashboard-chart"),
			options3
		);

		chart3.render();
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
					} else {
						console.log("error msg : ", obj.data);
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
				}, 1000);

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
	 * / Admin Chart
	 **/

	var _adminDashboardChart = $('#js_admin-dashboard-chart');

	if (_adminDashboardChart.length > 0){
		var _dataUrl 		= _adminDashboardChart.data('url');
		var formData 		= new FormData();
		formData.set('_token', _adminDashboardChart._getCsrfToken());


		$.ajax({
			url: _dataUrl,
			data: formData,
			type: "post",
			processData: false,
			contentType: false
		}).done(function(result) {
			var obj = $.parseJSON(result);
			chartInit(obj.dataChartSimpanan, obj.dataChartPinjaman);
			_adminDashboardChart._getCsrfToken(obj.csrf_token);
		});
	}

	/**
	 * / Admin Keanggotaan Page
	 **/

	var _formAdminPostMember = $('.js_admin-post-member');

	if (_formAdminPostMember.length > 0){
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

		imagePreview(_formAdminPostMember);
		validateForm(_formAdminPostMember);

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

	var _adminShowMemberDetail = $(".js_admin-show-member-detail");

	if (_adminShowMemberDetail.length > 0) {

		$("#adminShowMemberDetail").on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataMemberId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataMemberId', _dataMemberId);
			formData.set('_token', _adminShowMemberDetail._getCsrfToken());

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

				_adminShowMemberDetail._getCsrfToken(obj.csrf_token);
			});

		});
	}


	/**
	 * / Admin Simpanan Content Page
	 **/

	var _formAdminPostSimpananContent = $('.js_admin-post-simpanan-content');

	if (_formAdminPostSimpananContent.length > 0) {
		var _inputName				= $('input[name="simpanan_name"]');
		var _inputMinimumPrice		= $('input[name="simpanan_minimum_price"]');
		var _inputDuration			= $('input[name="simpanan_duration"]');
		var _inputContentStatus		= $('input[name="simpanan_content_status"]');
		var _inputContentType		= $('input[name="simpanan_content_type"]');
		var _inputBalasJasa			= $('input[name="simpanan_balas_jasa_percentage"]');
		var _inputBalasJasaPerdays	= $('input[name="simpanan_balas_jasa_perdays"]');

		validateForm(_formAdminPostSimpananContent);
		_inputName.rules('add', {
			required : true,
			messages: {
				required: "Nama Program Simpanan Tidak Boleh Kosong"
			}
		});
		_inputMinimumPrice.rules('add', {
			required : true,
			messages: {
				required: "Minimal Pembayaran Tidak Boleh Kosong"
			}
		});
		_inputDuration.rules('add', {
			required : true,
			messages: {
				required: "Durasi Program Simpanan Tidak Boleh Kosong"
			}
		});
		_inputContentStatus.rules('add', {
			required : true,
			messages: {
				required: "Status Program Simpanan Tidak Boleh Kosong"
			}
		});
		_inputContentType.rules('add', {
			required : true,
			messages: {
				required: "Tipe Program Simpanan Tidak Boleh Kosong"
			}
		});
		_inputBalasJasa.rules('add', {
			required : true,
			messages: {
				required: "Persentase Balas Jasa Tidak Boleh Kosong"
			}
		});
		_inputBalasJasaPerdays.rules('add', {
			required : true,
			messages: {
				required: "Durasi Pembayaran Balas Jasa Tidak Boleh Kosong"
			}
		});
	}


	/**
	 * / Admin Pinjaman Content Page
	 **/

	var _formAdminPostPinjamanContent = $('.js_admin-post-pinjaman-content');

	if (_formAdminPostPinjamanContent.length > 0) {
		var _inputName		= $('input[name="pinjaman_name"]');
		var _inputStatus	= $('select[name="pinjaman_content_status"]');
		var _tenorType 		= _formAdminPostPinjamanContent.find('.js_select-tenor-type');
		var _selectedTenorType = _tenorType.find(':selected').val().slice(0, -2);

		_formAdminPostPinjamanContent.find('.js_target-tenor-type').html(_selectedTenorType);

		_tenorType.on('change', function () {
			var _this = $(this);
			_formAdminPostPinjamanContent.find('.js_target-tenor-type').html(_this.val().slice(0, -2));
		});

		validateForm(_formAdminPostPinjamanContent);
		_inputName.rules('add', {
			required : true,
			messages: {
				required: "Nama Program Pinjaman Tidak Boleh Kosong"
			}
		});
		_inputStatus.rules('add', {
			required : true,
			messages: {
				required: "Status Program Pinjaman Tidak Boleh Kosong"
			}
		});

	}

	var _addTenorSetting = $('.js_btn-add-tenor-setting');

	if (_addTenorSetting.length > 0){
		var index = 1;

		_addTenorSetting.on("click", function () {
			index++;
			var _selectedTenorType = $('#pinjaman_content_tenor_type').find(':selected').val().slice(0, -2);
			$('#dynamic_field').append(`<tr id="row`+index+`" class="dynamix_added">
				<td>
					<div class="input-group">
						<input type="number" name="pinjaman_bunga[]" class="form-control" id="refferal_code" placeholder="1" aria-describedby="refferal_code">
						<div class="input-group-append">
							<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
								<div class="col-sm-1">
									<i class="">%</i>
								</div>
							</span>
						</div>
					</div
				</td>
				<td>
				<div class="input-group">
					<input type="number" name="pinjaman_tenor[]" class="form-control" id="refferal_code" placeholder="3" aria-describedby="refferal_code">
					<div class="input-group-append">
						<span class="input-group-text btn btn-outline-primary" id="inputGroupPrepend">
							<div class="col-sm-1">
								<span class="js_target-tenor-type">`+_selectedTenorType+`</span>
							</div>
						</span>
					</div>
					</div>
				</td>
				<td>
					<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>
				</td>
				</tr>`);
		});

		if ($(".btn_remove-setting").length > 0){
			console.log("btn-remove-setting");
			$(document).on('click', '.btn_remove-setting', function(e){
				var _this				= $(this);
				var _dataUrl 			= _formAdminPostPinjamanContent.data('url');
				var _contentDetailId 	= _this.data('id');
				var formData 			= new FormData();
				formData.set('contentDetailId', _contentDetailId);
				formData.set('_token', _formAdminPostPinjamanContent._getCsrfToken());

				console.log("clicked");
				$.ajax({
					url: _dataUrl,
					data: formData,
					type: "post",
					processData: false,
					contentType: false
				}).done(function(result) {
					var obj = $.parseJSON(result);

					if (obj.status == 'success') {
						var button_id = _this.attr("id");
						$('#row'+button_id+'').remove();
					}

					_formAdminPostPinjamanContent._getCsrfToken(obj.csrf_token);
				});
			});
		} else {
			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#row'+button_id+'').remove();
			});
		}
	}

	/**
	 *
	 *  Admin Pinjaman Anggota Page
	 *
	 * */

	var _formAdminPostPinjamanAnggota = $('.js_admin-post-pinjaman-anggota');

	if (_formAdminPostPinjamanAnggota.length > 0){
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataPinjamanId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataMemberPinjamanId', _dataPinjamanId);
			formData.set('_token', _formAdminPostPinjamanAnggota._getCsrfToken());

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
					var _tenorType = _dataPinjaman["pinjaman_member_tenor_type"].slice(0, -2);
					var _pinjamanAmmount = Number(_dataPinjaman['pinjaman_member_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_this.find('.js_pinjaman-member-uid').html(_dataPinjaman['member_referal_code']);
					_this.find('.js_pinjaman-member-full-name').html(_dataPinjaman['member_full_name']);
					_this.find('.js_pinjaman-created-at').html(_dataPinjaman['member_pinjaman_created_at']);
					_this.find('.js_pinjaman-description').html(_dataPinjaman['pinjaman_member_description']);
					_this.find('.js_pinjaman-member-ammount').html('<span> Rp. '+ _pinjamanAmmount +'</span> ');
					_this.find('.js_pinjaman-transaction-code').html(_dataPinjaman['pinjaman_transaction_code']);
					_this.find('.js_pinjaman-tenor').html(_dataPinjaman['pinjaman_member_tenor']+" "+_tenorType);
					_this.find('.js_pinjaman-bunga').html(_dataPinjaman['pinjaman_content_bunga']+" %");

					_this.find('input[name="member_id"]').val(_dataPinjaman["member_id"]);
					_this.find('input[name="pinjaman_id"]').val(_dataPinjaman["member_pinjaman_id"]);
					_this.find('input[name="pinjaman_transaction_code"]').val(_dataPinjaman["pinjaman_transaction_code"]);
					_this.find('input[name="pinjaman_ammount"]').val(_dataPinjaman["pinjaman_member_ammount"]);
					_this.find('input[name="pinjaman_tenor"]').val(_dataPinjaman["pinjaman_member_tenor"]);
					_this.find('input[name="pinjaman_tenor_type"]').val(_dataPinjaman["pinjaman_member_tenor_type"]);
					_this.find('input[name="pinjaman_bunga"]').val(_dataPinjaman["pinjaman_content_bunga"]);
				}

				_formAdminPostPinjamanAnggota._getCsrfToken(obj.csrf_token);
			});
		});

		_formAdminPostPinjamanAnggota.modalClose();
	}

	/**
	 *
	 *  Admin Tabungan Setting Page
	 *
	 * */
	var _formAdminPostTabunganAnggotaSetting = $(".js_admin-post-tabungan-anggota-setting");

	if (_formAdminPostTabunganAnggotaSetting.length > 0){
		var _inputRequiredAmmount		= $('input[name="required_ammount"]');
		var _inputInterestPercentage	= $('input[name="interest_percentage"]');
		var _inputAdminFee				= $('input[name="admin_fee"]');

		validateForm(_formAdminPostTabunganAnggotaSetting);

		_inputRequiredAmmount.rules('add', {
			required : true,
			messages: {
				required: "Minimal Tabungan Tidak Boleh Kosong",
			}
		});
		_inputInterestPercentage.rules('add', {
			required : true,
			messages: {
				required: "Persentase Imbal Jasa Tidak Boleh Kosong",
			}
		});
		_inputAdminFee.rules('add', {
			required : true,
			messages: {
				required: "Biaya Admin Tidak Boleh Kosong",
			}
		});
	}

	/**
	 * / Admin Tabungan Anggota Approval Page
	 **/

	var _formAdminTabunganAnggotaApproval = $('.js_form-admin-tabungan-anggota-approval');

	if (_formAdminTabunganAnggotaApproval.length > 0) {
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataTabunganId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('tabunganMemberId', _dataTabunganId);
			formData.set('_token', _formAdminTabunganAnggotaApproval._getCsrfToken());

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
					var _actualAmmountFormat	= Number(obj.data['tabungan_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_this.find('.js_member-uid').html(obj.data['member_referal_code']);
					_this.find('.js_tabungan-member-full-name').html(obj.data['member_full_name']);
					_this.find('.js_tabungan-member-phone-number').html(obj.data['member_phone_number']);
					_this.find('.js_tabungan-member-email').html(obj.data['member_email']);

					_this.find('.js_tabungan-code').html(obj.data['tabungan_tr_code']);
					_this.find('.js_tabungan-gateway').html(obj.data['nama_bank']);
					_this.find('.js_tabungan-gateway-number').html(obj.data['nomor_rekening']);
					_this.find('.js_tabungan-gateway-account').html(obj.data['nama_pemilik_account']);
					_this.find('.js_tabungan-ammount').html('<span> Rp. '+_actualAmmountFormat+'</span> ');

					_formAdminTabunganAnggotaApproval.find('input[name="tabungan_member_id"]').val(_dataTabunganId);
					_formAdminTabunganAnggotaApproval.find('input[name="member_id"]').val(obj.data['member_id']);
					_formAdminTabunganAnggotaApproval.find('input[name="tabungan_tr_code"]').val(obj.data['tabungan_tr_code']);
				}

				_formAdminTabunganAnggotaApproval._getCsrfToken(obj.csrf_token);
			});
		});
	}

	/**
	 * / Admin Tabungan Anggota TF Approval Page
	 **/

	var _formAdminTabunganAnggotaTfApproval = $('.js_admin-tabungan-tf-anggota-approval');

	if (_formAdminTabunganAnggotaTfApproval.length > 0) {
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this				= $(this);
			var _dataUrl 			= _this.data('url');
			var _dataTabunganTfId	= $(e.relatedTarget).data('id');
			var _dataTabunganMbaId	= $(e.relatedTarget).data('mba');
			var formData 			= new FormData();
			formData.set('tabunganTfMemberId', _dataTabunganTfId);
			formData.set('tabunganTfMemberBankId', _dataTabunganMbaId);
			formData.set('_token', _formAdminTabunganAnggotaTfApproval._getCsrfToken());

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
					var _actualAmmountFormat	= Number(obj.data['tabungan_tf_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_this.find('.js_member-uid').html(obj.data['member_referal_code']);
					_this.find('.js_tabungan-tf-member-full-name').html(obj.data['member_full_name']);
					_this.find('.js_tabungan-tf-member-phone-number').html(obj.data['member_phone_number']);
					_this.find('.js_tabungan-tf-member-email').html(obj.data['member_email']);

					_this.find('.js_tabungan-tf-code').html(obj.data['tabungan_tf_tr_code']);
					_this.find('.js_tabungan-tf-type').html(obj.data['tabungan_tf_type']);
					_this.find('.js_tabungan-tf-ammount').html('<span> Rp. '+_actualAmmountFormat+'</span> ');

					if (_dataTabunganMbaId){
						$('.js_tabungan-transfer-gateway-info').append(`<tr class="dynamic_field">
								<td>Gateway</td>
								<td class="js_tabungan-tf-gateway">
								`+ obj.data['bank_account_name'] +`
								</td>
							</tr>
							<tr class="dynamic_field">
								<td>Nomor Rekening</td>
								<td class="js_tabungan-tf-gateway-number">
								`+ obj.data['bank_account_number'] +`
								</td>
							</tr>
							<tr class="dynamic_field">
								<td>Nama Pemilik Akun</td>
								<td class="js_tabungan-tf-gateway-account">
								`+ obj.data['bank_account_owner'] +`
								</td>
							</tr>`);
					}
				}

				_formAdminTabunganAnggotaTfApproval.find('input[name="tabungan_tf_id"]').val(_dataTabunganTfId);
				_formAdminTabunganAnggotaTfApproval.find('input[name="tabungan_tf_member_bank_id"]').val(_dataTabunganMbaId);
				_formAdminTabunganAnggotaTfApproval.find('input[name="member_id"]').val(obj.data['member_id']);
				_formAdminTabunganAnggotaTfApproval.find('input[name="tabungan_tf_tr_code"]').val(obj.data['tabungan_tf_tr_code']);

				_formAdminTabunganAnggotaTfApproval._getCsrfToken(obj.csrf_token);
			});

		});

		_formAdminTabunganAnggotaTfApproval.modalClose();
		$('#exampleModal').on('hidden.bs.modal', function () {
			var _this = $(this);
			_this.find('.dynamic_field').remove();
		});
	}

	/**
	 * / Admin Deposit Page
	 **/

	var _formAdminMemberDeposit = $('.js_form-admin-member-deposit');

	if (_formAdminMemberDeposit.length > 0){
		validateForm(_formAdminMemberDeposit);
	}

	var _showAdminMemberDepositDetail = $('.js_show-admin-member-deposit-detail');

	if (_showAdminMemberDepositDetail.length > 0) {
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataDepositId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataDepositId', _dataDepositId);
			formData.set('_token', _formAdminMemberDeposit._getCsrfToken());

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

					var _totalTransfer			= Number(obj.data[0]['deposit_ammount']) + Number(obj.data[0]['deposit_last_code']);
					var _totalTransferFormat	= _totalTransfer.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					var _actualAmmountFormat	= Number(obj.data[0]['deposit_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_this.find('.js_member-uid').html(obj.data[0]['member_referal_code']);
					_this.find('.js_deposit-member-full-name').html(obj.data[0]['member_full_name']);
					_this.find('.js_deposit-member-phone-number').html(obj.data[0]['member_phone_number']);
					_this.find('.js_deposit-member-email').html(obj.data[0]['member_email']);

					_this.find('.js_deposit-code').html(obj.data[0]['deposit_code']);
					_this.find('.js_deposit-gateway').html(obj.data[0]['nama_bank']);
					_this.find('.js_deposit-gateway-number').html(obj.data[0]['nomor_rekening']);
					_this.find('.js_deposit-gateway-account').html(obj.data[0]['nama_pemilik_account']);
					_this.find('.js_deposit-last-code').html(obj.data[0]['deposit_last_code']);
					_this.find('.js_deposit-ammount').html('<span> Rp. '+_totalTransferFormat+'</span> ');
					_this.find('.js_deposit-actual-ammount').html('<span> Rp. '+_actualAmmountFormat+'</span> ');

					_formAdminMemberDeposit.find('input[name="deposit_id"]').val(_dataDepositId);
				}

				_formAdminMemberDeposit._getCsrfToken(obj.csrf_token);
			});

		});
	}


	/**
	 * / Admin Withdrawal Page
	 **/

	var _formAdminMemberWithdrawal = $('.js_form-admin-member-withdrawal');

	if (_formAdminMemberWithdrawal.length > 0){
		validateForm(_formAdminMemberWithdrawal);
	}

	var _showAdminMemberWithdrawalDetail = $('.js_show-admin-member-withdrawal-detail');

	if (_showAdminMemberWithdrawalDetail.length > 0) {
		$('#exampleModal').on('show.bs.modal', function(e){
			var _this				= $(this);
			var _dataUrl 			= _this.data('url');
			var _dataWithdrawalId	= $(e.relatedTarget).data('id');
			var formData 			= new FormData();
			formData.set('dataWithdrawalId', _dataWithdrawalId);
			formData.set('_token', _formAdminMemberWithdrawal._getCsrfToken());

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

					_formAdminMemberWithdrawal.find('input[name="withdrawal_id"]').val(_dataWithdrawalId);

					var _wdAmmount = Number(obj.data[0]['wd_ammount']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					_this.find('.js_member-uid').html(obj.data[0]['member_referal_code']);
					_this.find('.js_withdrawal-member-full-name').html(obj.data[0]['member_full_name']);
					_this.find('.js_withdrawal-member-phone-number').html(obj.data[0]['member_phone_number']);
					_this.find('.js_withdrawal-member-email').html(obj.data[0]['member_email']);

					_this.find('.js_withdrawal-code').html(obj.data[0]['wd_transaction_code']);
					_this.find('.js_withdrawal-gateway').html(obj.data[0]['bank_account_name']);
					_this.find('.js_withdrawal-gateway-number').html(obj.data[0]['bank_account_number']);
					_this.find('.js_withdrawal-gateway-account').html(obj.data[0]['bank_account_owner']);
					_this.find('.js_withdrawal-ammount').html('<span> Rp. '+ _wdAmmount +'</span> ');

				}

				_formAdminMemberWithdrawal._getCsrfToken(obj.csrf_token);
			});

		});
	}


	/**
	 * / Admin Olshop Content Page
	 **/

	var _formAdminOlshopContet = $('.js_admin-post-olshop');

	if (_formAdminOlshopContet.length > 0){
		var _inputName			= $('input[name="olshop_product_name"]');
		var _inputPrice			= $('input[name="olshop_product_price"]');
		var _inputStatus		= $('input[name="olshop_product_status"]');
		var _inputImg			= $('input[name="olshop_product_img"]');

		imagePreview(_formAdminOlshopContet);
		validateForm(_formAdminOlshopContet);

		_inputName.rules('add', {
			required : true,
			messages: {
				required: "Nama Produk Tidak Boleh Kosong",
			}
		});
		_inputPrice.rules('add', {
			required : true,
			messages: {
				required: "Harga L-poin Tidak Boleh Kosong"
			}
		});
		_inputStatus.rules('add', {
			required : true,
			messages: {
				required: "Status Produk KTP Tidak Boleh Kosong"
			}
		});
		_inputImg.rules('add', {
			required : true,
			messages: {
				required: "Thumbnail Produk Tidak Boleh Kosong"
			}
		});

	}

	/**
	 *
	 *  Member Online Shop Page
	 *
	 * */
	var _showLenteraOlshopProductDetail = $('.js_admin-show-checkout-product');

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
					_this.find('input[name="member_id"]').val(obj.data['member_id']);
					_this.find('input[name="checkout_code"]').val(obj.data['checkout_code']);

					_this.find('.js_member-uid').html(obj.data['member_referal_code']);
					_this.find('.js_member-full-name').html(obj.data['member_full_name']);
					_this.find('.js_member-phone-number').html(obj.data['member_phone_number']);
					_this.find('.js_member-email').html(obj.data['member_email']);

					var _checkoutPrice = Number(obj.data['olshop_product_price']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					var _checkoutTotalAmmount = Number(obj.data['olshop_product_price'] * obj.data['checkout_quantity']).toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					_this.find('.js_checkout-code').html(obj.data['checkout_code']);
					_this.find('.js_product-name').html(obj.data['olshop_product_name']);
					_this.find('.js_product-price').html('<span> Rp. '+ _checkoutPrice +'</span> ');
					_this.find('.js_checkout-quantity').html(obj.data['checkout_quantity']);
					_this.find('.js_checkout-total-ammount').html('<span> Rp. '+ _checkoutTotalAmmount +'</span> ');
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
	 * / Admin Pengurus Page
	 **/

	var _formAdminPengurus	= $('.js_admin-pengurus-post');

	if (_formAdminPengurus.length > 0 ) {
		var _inputEmail			= $('input[name="admin_email"]');
		var _inputPhone			= $('input[name="admin_phone_number"]');
		var _inputKtp			= $('input[name="admin_ktp_number"]');
		var _inputKtpImg		= $('input[name="admin_ktp_image"]');
		var _inputName			= $('input[name="admin_full_name"]');
		var _inputBirthPlace	= $('input[name="admin_birth_place"]');
		var _inputBirthDate		= $('input[name="admin_birth_date"]');
		var _inputLastEducation	= $('input[name="admin_last_education"]');
		var _inputJob			= $('input[name="admin_job"]');
		var _inputAddress		= $('input[name="admin_address"]');
		var _inputKelurahan		= $('input[name="admin_kelurahan"]');
		var _inputKecamatan		= $('input[name="admin_kecamatan"]');
		var _inputKota			= $('input[name="admin_kota"]');
		var _inputPrvinsi		= $('input[name="admin_provinsi"]');
		var _inputKodePos		= $('input[name="admin_kode_pos"]');

		imagePreview(_formAdminPengurus);
		validateForm(_formAdminPengurus);

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

	var _showAdminPengurusDetail = $('.js_admin-pengurus-detail');

	if (_showAdminPengurusDetail.length > 0){

		$('#showAdminPengurusDetail').on('show.bs.modal', function(e){
			var _this			= $(this);
			var _dataUrl 		= _this.data('url');
			var _dataAdminId	= $(e.relatedTarget).data('id');
			var formData 		= new FormData();
			formData.set('dataAdminPengurusId', _dataAdminId);
			formData.set('_token', _showAdminPengurusDetail._getCsrfToken());

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
					_this.find('.js_admin-full-name').html(obj.data[0]['admin_full_name']);
					_this.find('.js_admin-verified').html(obj.data[0]['admin_is_verified'] ? "Email Telah Diverifikasi" : "Belum Verifikasi Email");
					_this.find('.js_admin-kyc').html(obj.data[0]['admin_is_kyc'] ? "Selesai" : "Belum Selesai");
				}

				_showAdminPengurusDetail._getCsrfToken(obj.csrf_token);
			}).fail(function (error, abc, dfg) {
				console.log("error msg : ", error);
			});

		});
	}


	/**
	 * / Admin Site Setting Page
	 **/

	/*
	* 	Form Payment Gateway Page
	* */
	var _formPaymentGateway = $('.js_form-payment-gateway');

	if (_formPaymentGateway.length > 0) {
		var _inputNamaBank				= $('input[name="nama_bank"]');
		var _inputNomorRekening			= $('input[name="nomor_rekening"]');
		var _inputNamaPemilikAccount	= $('input[name="nama_pemilik_account"]');

		_formPaymentGateway.modalClose();
		validateForm(_formPaymentGateway);

		_inputNamaBank.rules('add', {
			required : true,
			messages: {
				required: "Nama Bank Tidak Boleh Kosong",
			}
		});
		_inputNomorRekening.rules('add', {
			required : true,
			messages: {
				required: "Nomor Rekening Tidak Boleh Kosong"
			}
		});
		_inputNamaPemilikAccount.rules('add', {
			required : true,
			messages: {
				required: "Nama Pemilik Akun Tidak Boleh Kosong"
			}
		});
	}

	/*
	* 	Form Setting Company Page
	* */
	var _formSettingCompany	= $('.js_admin-setting-company');

	if (_formSettingCompany.length > 0) {
		var _inputSetcomDocument= $('input[name="setcom_document"]');

		validateForm(_formSettingCompany);
		_inputSetcomDocument.rules('add', {
			required : true,
			messages: {
				required: "File AD/ART Tidak Boleh Kosong"
			}
		});
	}


	/**
	 * / Admin Profile Page
	 **/

	var _formEditProfile = $('.js_admin-profile-edit');

	if (_formEditProfile.length > 0){
		var getAppProfileBody 	= $('.app-profile');
		var _inputEmail			= $('input[name="admin_email"]');
		var _inputPhone			= $('input[name="admin_phone_number"]');
		var _inputKtp			= $('input[name="admin_ktp_number"]');
		var _inputKtpImg		= $('input[name="admin_ktp_image"]');
		var _inputName			= $('input[name="admin_full_name"]');
		var _inputBirthPlace	= $('input[name="admin_birth_place"]');
		var _inputBirthDate		= $('input[name="admin_birth_date"]');
		var _inputLastEducation	= $('input[name="admin_last_education"]');
		var _inputJob			= $('input[name="admin_job"]');
		var _inputAddress		= $('input[name="admin_address"]');
		var _inputKelurahan		= $('input[name="admin_kelurahan"]');
		var _inputKecamatan		= $('input[name="admin_kecamatan"]');
		var _inputKota			= $('input[name="admin_kota"]');
		var _inputPrvinsi		= $('input[name="admin_provinsi"]');
		var _inputKodePos		= $('input[name="admin_kode_pos"]');

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
		// _inputKtpImg.rules('add', {
		// 	required : true,
		// 	messages: {
		// 		required: "Gambar KTP Tidak Boleh Kosong"
		// 	}
		// });
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
	* 	Admin Profile Edit Password Page
	* */
	var _formAdminEditPassword = $('.js_admin-profile-password-edit');

	if (_formAdminEditPassword.length > 0){
		var _inputPassword			= $('input[name="admin_password"]');
		var _inputPasswordRetype	= $('input[name="admin_password_retype"]');

		validateForm(_formAdminEditPassword);

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
			equalTo: "#admin_password",
			messages: {
				required: "Konfirmasi Password Tidak Boleh Kosong",
				equalTo: "Konfirmasi Password Tidak Sama"
			}
		});
	}

	/*
	* 	Admin Profile Edit Transaction Code Page
	* */
	var _formAdminEditTransactionCode = $('.js_admin-profile-transaction-code-edit');

	if (_formAdminEditTransactionCode.length > 0){
		var _inputTransactionCode		= $('input[name="admin_transaction_code"]');
		var _inputTransactionCodeRetype	= $('input[name="admin_transaction_code_retype"]');

		validateForm(_formAdminEditTransactionCode);

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
			equalTo: "#admin_transaction_code",
			messages: {
				required: "Konfirmasi kode transaksi Tidak Boleh Kosong",
				equalTo: "Konfirmasi kode transaksi Tidak Sama"
			}
		});
	}

})(jQuery);
