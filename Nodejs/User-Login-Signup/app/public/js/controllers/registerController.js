// This controller is created for User Registration
function RegisterController()
{
    // redirect to homepage when cancel button is clicked  & clear all popup form data//
    $('#account-form-btn1').click(function(){ 
         $('#create_account').modal('hide');
         $('#account-form #name').val('');
         $('#account-form #email').val('');
         $('#account-form #phone').val('');
         $('#account-form #username').val('');
         $('#account-form #password').val('');
    });

    // redirect to homepage on new account creation, add short delay so user can read alert window //
    $('.modal-alert #ok').click(function(){ setTimeout(function(){window.location.href = '/';}, 300)});
}