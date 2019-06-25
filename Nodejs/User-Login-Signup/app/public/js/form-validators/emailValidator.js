
function EmailValidator()
{
    // bind this to _local for anonymous functions //
    var _local = this;

}

//validate email
EmailValidator.prototype.validateEmail = function(e)
{
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(e);
}

//Email error alert
EmailValidator.prototype.showEmailAlert = function(m)
{
    this.retrievePasswordAlert.attr('class', 'alert alert-danger');
    this.retrievePasswordAlert.html(m);
    this.retrievePasswordAlert.show();
}

//Hide email alert
EmailValidator.prototype.hideEmailAlert = function()
{
    this.retrievePasswordAlert.hide();
}

//Success email 
EmailValidator.prototype.showEmailSuccess = function(m)
{
    this.retrievePasswordAlert.attr('class', 'alert alert-success');
    this.retrievePasswordAlert.html(m);
    this.retrievePasswordAlert.fadeIn(500);
}