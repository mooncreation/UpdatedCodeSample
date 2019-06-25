
$(document).ready(function () {

    var lv = new LoginValidator();
    
    //Clear Login form data and hide error message
    $('#login_form .close').on('click', function (e) {
        $('#form-login #username').val('');
        $('#form-login #password').val('');
        $('.error_message').hide();
    });
    
    // Open Login form
    $('.button-register').on('click', function (e) {
        $('#create_account').modal('show');

    });
    $('.button-login-in').on('click', function (e) {
        $('#login_form').modal('show');

    });
    
    // Submit login form
    $('#login_form #form-login').ajaxForm({

        beforeSubmit: function (formData, jqForm, options) {
            if (lv.validateForm() == false) {
                return false;
            } else {
                return true;
            }
        },
        success: function (responseText, status, xhr, $form) {
            if (status == 'success')
                window.location.href = '/profile';
        },
        error: function (e) {
            $('.error_message').show();
            $("#login_form #form-login .error_msg").addClass("error_message");
            $('#login_form #form-login .error_message').text('Please check your username and/or password');
        }
    });
    $('#username').focus();
});
