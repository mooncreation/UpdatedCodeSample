
$(document).ready(function () {

    // Create profile validatior object
    var av = new ProfileValidator();
    
    // Create registration object
    var sc = new RegisterController();
    
    // New user registration process
    $('#account-form').ajaxForm({
        
        //Before submit validate form
        beforeSubmit: function (formData, jqForm, options) {
                return true;
        },
        
        //Ajax Success
        success: function (responseText, status, xhr, $form) {
            if (status == 'success') {
                $('#create_account').modal('hide');
                $('#modal-alert').modal('show');
            }
        },
        
        //Ajax Error to check if email/username already exists or not.
        error: function (e) {
            if (e.responseText == 'email-taken') {
                $('#modal-form-errors').modal('show');
                $('#mmodal-form-errors').modal({show: false, keyboard: false, backdrop: 'static'});
                $('#modal-form-errors .modal-header h4').text('Please correct the following problems :');
                $('#modal-form-errors .modal-body p').html('That Email is already in use.');
            } else if (e.responseText == 'username-taken') {
                $('#modal-form-errors').modal('show');
                $('#mmodal-form-errors').modal({show: false, keyboard: false, backdrop: 'static'});
                $('#modal-form-errors .modal-header h4').text('Please correct the following problems :');
                $('#modal-form-errors .modal-body p').html('That username is already in use.');
            }
        }
    });
    $('#name').focus();

    //New user registration form //
    $('#account-form h2').text('Signup');
    $('#account-form #sub1').text('Please tell us a little about yourself');
    $('#account-form #sub2').text('Choose your username & password');
    $('#account-form-btn1').html('Cancel');
    $('#account-form-btn2').html('Submit');
    $('#account-form-btn2').addClass('btn-success');

    // New user create notification in popup //
    $('#modal-alert').modal({show: false, keyboard: false, backdrop: 'static'});
    $('#modal-alert .modal-header h4').text('Account Created!');
    $('#modal-alert .modal-body p').html('Your account has been created.</br>Click OK to return to the login page.');

});