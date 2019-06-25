
function ProfileValidator()
{
    // build array maps of the form inputs & control groups //
    this.formFields = [$('#name'), $('#email'), $('#username'), $('#password')];
    this.controlGroups = [$('#name-cg'), $('#email-cg'), $('#user-cg'), $('#pass-cg')];

    // bind the form-error modal window to this controller to display any errors //
    this.alert = $('#modal-form-errors');
    this.alert.modal({ show : false, keyboard : true, backdrop : true});

    //validate User name
    this.validateName = function(s)
    {
        return s.length >= 3;
    }
    
    //Validate password length
    this.validatePassword = function(s)
    {
        // Edit profile remove password validation.
        if ($('#userId').val() && s===''){
                return true;
        }	else{
                return s.length >= 6;
        }
    }
    
    //Validate email
    this.validateEmail = function(e)
    {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(e);
    }

    //Modal structure for error popup
    this.showErrors = function(a)
    {
        $('#modal-form-errors .modal-body p').text('Please correct the following problems :');
        var ul = $('#modal-form-errors .modal-body ul');
                ul.empty();
        for (var i=0; i < a.length; i++) ul.append('<li>'+a[i]+'</li>');
        this.alert.modal('show');
    }

}

//Check if email is already exists.
ProfileValidator.prototype.showInvalidEmail = function()
{
    $('#modal-form-errors .modal-body p').text('Please correct the following problems :');
    var ul = $('#modal-form-errors .modal-body ul');
    ul.empty();
    for (var i=0; i < a.length; i++) ul.append('<li>That email address is already in use</li>');
    this.alert.modal('show');
}

//Check if Username is already exists.
ProfileValidator.prototype.showInvalidUserName = function()
{
    this.controlGroups[2].addClass('error');
    this.showErrors(['That username is already in use.']);
}

	