
$(document).ready(function () {

    var hc = new ProfileController();
    var av = new ProfileValidator();
    
    //Profile form submit code
    $('#account-form').ajaxForm({
        beforeSubmit: function (formData, jqForm, options) {
            return true;
        },
        success: function (responseText, status, xhr, $form) {
            if (status == 'success')
                hc.onUpdateSuccess();
        },
        error: function (e) {
            if (e.responseText == 'email-taken') {
                av.showInvalidEmail();
            } else if (e.responseText == 'username-taken') {
                av.showInvalidUserName();
            }
        }
    });
    $('#name').focus();

    // Profile view //
    $('#account-form h2').text('Update Profile');
    $('#account-form #sub1').text('Here are the current settings for your account.');
    $('#username').attr('disabled', 'disabled');
    $('#account-form-btn1').html('Delete');
    $('#account-form-btn1').addClass('btn-danger');
    $('#account-form-btn2').html('Update');

    // Delete User account confirmation popup //
    $('.modal-confirm').modal({show: false, keyboard: true, backdrop: true});
    $('.modal-confirm .modal-header h4').text('Delete Account');
    $('.modal-confirm .modal-body p').html('Are you sure you want to delete your account?');
    $('.modal-confirm .cancel').html('Cancel');
    $('.modal-confirm .submit').html('Delete');
    $('.modal-confirm .submit').addClass('btn-danger');

});
