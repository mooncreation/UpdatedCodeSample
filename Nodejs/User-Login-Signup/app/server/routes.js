
// Include profile manager module
var AM = require('./modules/profile-manager');


module.exports = function (app) {

    // Open Login popup //
    app.get('/', function (req, res) {
        // check if the user's credentials are saved in a cookie //
        if (req.cookies.username == undefined || req.cookies.password == undefined) {
            res.render('login', {title: 'Hello - Please Login To Your Account'});
        } else {
            // attempt automatic login //
            AM.autoLogin(req.cookies.username, req.cookies.password, function (o) {
                if (o != null) {
                    req.session.username = o;
                    res.redirect('/profile');
                } else {
                    res.render('login', {title: 'Hello - Please Login To Your Account'});
                }
            });
        }
    });

    
    app.post('/', function (req, res) {
        // Check Login credentials are valid or not
        if (req.body.name == undefined) {
            AM.manualLogin(req.body['username'], req.body['password'], function (e, o) {
                if (!o) {
                    res.status(400).send(e);
                } else {
                    req.session.username = o;
                    res.status(200).send(o);
                }
            });
        } else {
            
            // Create New User account
            AM.addNewAccount({
                name: req.body['name'],
                email: req.body['email'],
                username: req.body['username'],
                password: req.body['password'],
                phone: req.body['phone']
            }, function (e) {
                if (e) {
                    res.status(400).send(e);
                } else {
                    res.redirect('/profile');
                }
            });
        }
    });

    // Check user login and redirect to User profile page //
    app.get('/profile', function (req, res) {
        if (req.session.username == null) {
            
            // if user is not logged-in redirect back to login page //
            res.redirect('/');
        } else {
            res.render('profile', {
                title: 'Control Panel',
                udata: req.session.username
            });
        }
    });

    // User profile update
    app.post('/profile', function (req, res) {
        if (req.session.username == null) {

            res.redirect('/');
        } else {
            AM.updateAccount({
                id: req.session.username._id,
                name: req.body['name'],
                email: req.body['email'],
                password: req.body['password'],
                phone: req.body['phone']
            }, function (e, o) {
                if (e) {
                    res.status(400).send('error-updating-account');
                } else {
                    req.session.username = o;
                    
                    // update the user's login cookies if they exists //
                    if (req.cookies.username != undefined && req.cookies.password != undefined) {
                        res.cookie('username', o.username, {maxAge: 900000});
                        res.cookie('password', o.password, {maxAge: 900000});
                    }
                    res.status(200).send('ok');
                }
            });
        }
    });

    // User logout 
    app.post('/logout', function (req, res) {
        res.clearCookie('username');
        res.clearCookie('password');
        req.session.destroy(function (e) {
            res.status(200).send('ok');
        });
    })

    // Render registration page
    app.get('/register', function (req, res) {
        res.render('register', {title: 'Signup'});
    });

    // User registration validate
    app.post('/register', function (req, res) {
        AM.addNewAccount({
            name: req.body['name'],
            email: req.body['email'],
            username: req.body['username'],
            password: req.body['password'],
            phone: req.body['phone']
        }, function (e) {
            if (e) {
                res.status(400).send(e);
            } else {
                res.status(200).send('ok');
            }
        });
    });

   
   // Delete User account.
    app.post('/delete', function (req, res) {
        AM.deleteAccount(req.body.id, function (e, obj) {
            if (!e) {
                res.clearCookie('password');
                res.clearCookie('password');
                req.session.destroy(function (e) {
                    res.status(200).send('ok');
                });
            } else {
                res.status(400).send('record not found');
            }
        });
    });
    
    // If page not found 
    app.get('*', function (req, res) {
        res.render('404', {title: 'Page Not Found'});
    });

};
