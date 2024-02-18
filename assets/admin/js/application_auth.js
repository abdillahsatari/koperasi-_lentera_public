(function ($) {

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
			errorPlacement: function(error, element) {
				if (element.hasClass('js-input-with-plugin')){
					error.appendTo(element.parent("div").find(".js_input-error-placement"));
				} else {
					error.insertAfter(element);
				}
			},
		});

		$form.find('.js-form_action_btn').on('click', function (event) {
			event.preventDefault();
			if ($form.valid()) {
				$form.submit();
			}
		});
	}

	$(".js_show-hide-password").on('click', function (event) {
		event.preventDefault();
		var _this = $(this);
		var _inputPassword = _this.parent("div").parent("div").find('input[name="password"]');

		if (_inputPassword.attr("type") == "text") {
			_inputPassword.attr('type', 'password');
			_this.find('.js_show-hide-icons').addClass("bx-hide");
			_this.find('.js_show-hide-icons').removeClass("bx-show");
		} else if (_inputPassword.attr("type") == "password") {
			_inputPassword.attr('type', 'text');
			_this.find('.js_show-hide-icons').removeClass("bx-hide");
			_this.find('.js_show-hide-icons').addClass("bx-show");
		}
	});

	/**
	 *
	 * Member Authentication
	 *
	 * */

	var _formAuthentication = $('.js-form_authentication');

	if (_formAuthentication.length > 0) {
		var _dataSession 	= _formAuthentication.data("session");
		var _uid			= _dataSession == "Member" ? "No. Hp" : "Email";
		var _inputUid		= $('input[name="uid"]');
		var _inputPassword	= $('input[name="password"]');

		validateForm(_formAuthentication);

		_inputUid.rules('add', {
			required : true,
			messages: {
				required: _uid + " Tidak Boleh Kosong",
			}
		});

		_inputPassword.rules('add', {
			required : true,
			messages: {
				required: "Password Tidak Boleh Kosong"
			}
		});

	}

	/**
	 *
	 * Member Registration
	 *
	 * */

	var _formMemberRegistration = $('.js-form_registration');

	if (_formMemberRegistration.length > 0){

		var _inputName	= $('input[name="member_full_name"]');
		var _inputEmail	= $('input[name="member_email"]');
		var _inputPhone	= $('input[name="member_phone_number"]');
		var _phoneNumberIsValid = true;

		_inputPhone.on('change', function () {
			var _this			= $(this);
			var _url 			= _formMemberRegistration.data("url");
			var _inputValue		= _this.val();
			var formData 		= new FormData();
			formData.set('dataMemberPhoneNumber', _inputValue);
			formData.set('_token', _formMemberRegistration._getCsrfToken());

			$.ajax({
				url: _url,
				data: formData,
				type: "post",
				processData: false,
				contentType: false
			}).done(function(result) {
				var obj = $.parseJSON(result);
				(obj.data > 0) ? _phoneNumberIsValid = false : _phoneNumberIsValid = true ;
				_formMemberRegistration._getCsrfToken(obj.csrf_token);
			});
		});

		validateForm(_formMemberRegistration);

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


})(jQuery);
