
function LoginValidator()
{
    // bind a simple alert window to this controller to display any errors //
    this.loginErrors = $('#modal-alert');
    
    //Modal for login error
    this.showLoginError = function(t, m)
    {
        $('#modal-alert').modal('show');
        $("#modal-alert .modal-header").addClass("error_header");
        $('#modal-alert .modal-header h4').text(t);
        $('#modal-alert .modal-body').html(m);
        this.loginErrors.modal('show');
    }
}

//validate Login form
LoginValidator.prototype.validateForm = function(m)
{
    if ($('#login_form #username').val() == '' || $('#login_form #username').val() == '') {
        $('.error_message').hide();
        $('.errormessage').show();
        $(".user .error").addClass("errormessage");
        $('.user .errormessage').text('Please enter username');
        if ($('#login_form #password').val() == '') {
            $('.errormessage').show();
            $('.error_message').hide();
            $(".pass .error").addClass("errormessage");
            $('.pass .errormessage').text('Please enter username');
            $('.pass .errormessage').text('Please enter password');
        }
        return false;
    }
    else if ($('#login_form #password').val() == '') {
        $('.errormessage').show();
        $('.error_message').hide();
        $(".pass .error").addClass("errormessage");
        $('.pass .errormessage').text('Please enter password');
        return false;
    }
    else {
        $('.errormessage').hide();
        return true;
    }
}