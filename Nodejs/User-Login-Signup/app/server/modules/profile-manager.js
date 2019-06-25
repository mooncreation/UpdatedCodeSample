/* By - Acquaint SoftTech Pvt. Ltd.
 * Here you can find database related functions like Insert, Update, Delete and Select.*/

var crypto = require('crypto'); // Password encrypt module
var MongoDB = require('mongodb').Db; // MongoDb database module
var Server = require('mongodb').Server; // MongoDb server module
var moment = require('moment'); // Moment module

/* Start: ESTABLISH DATABASE CONNECTION */
var dbName = process.env.DB_NAME || 'node-login'; // Db name
var dbHost = process.env.DB_HOST || 'localhost' // Host name
var dbPort = process.env.DB_PORT || 27017; // Port
/* End: ESTABLISH DATABASE CONNECTION */

// Create MongoDb connection string
var db = new MongoDB(dbName, new Server(dbHost, dbPort, {auto_reconnect: true}), {w: 1});

// Check DB connection string authentication
db.open(function (e, d) {
    if (e) {
        console.log(e);
    } else {
        if (process.env.NODE_ENV == 'live') {
            db.authenticate(process.env.DB_USER, process.env.DB_PASS, function (e, res) {
                if (e) {
                    console.log('mongo :: error: not authenticated', e);
                } else {
                    console.log('mongo :: authenticated and connected to database :: "' + dbName + '"');
                }
            });
        } else {
            console.log('mongo :: connected to database :: "' + dbName + '"');
        }
    }
});

var accounts = db.collection('accounts'); // "accounts" is DB collection/table name

/* Check automatic Databse login validation */
exports.autoLogin = function (username, password, callback)
{
    accounts.findOne({username: username}, function (e, o) {
        if (o) {
            o.password == password ? callback(o) : callback(null);
        } else {
            callback(null);
        }
    });
}

/* Check manual Databse login validation */
exports.manualLogin = function (username, password, callback)
{
    accounts.findOne({username: username}, function (e, o) {
        if (o == null) {
            callback('user-not-found');
        } else {
            validatePassword(password, o.password, function (err, res) {
                if (res) {
                    callback(null, o);
                } else {
                    callback('invalid-password');
                }
            });
        }
    });
}

/* MongoDb Insert User record*/
exports.addNewAccount = function (newData, callback)
{
    accounts.findOne({username: newData.username}, function (e, o) {
        if (o) {
            callback('username-taken');
        } else {
            accounts.findOne({email: newData.email}, function (e, o) {
                if (o) {
                    callback('email-taken');
                } else {
                    saltAndHash(newData.password, function (hash) {
                        newData.password = hash;
                        // append date stamp when record was created //
                        newData.date = moment().format('MMMM Do YYYY, h:mm:ss a');
                        accounts.insert(newData, {safe: true}, callback);
                    });
                }
            });
        }
    });
}

/* MongoDb Update User record*/
exports.updateAccount = function (newData, callback)
{
    accounts.findOne({_id: getObjectId(newData.id)}, function (e, o) {
        o.name = newData.name;
        o.email = newData.email;
        o.phone = newData.phone;
        if (newData.password == '') {
            accounts.save(o, {safe: true}, function (e) {
                if (e)
                    callback(e);
                else
                    callback(null, o);
            });
        } else {
            saltAndHash(newData.password, function (hash) {
                o.password = hash;
                accounts.save(o, {safe: true}, function (e) {
                    if (e)
                        callback(e);
                    else
                        callback(null, o);
                });
            });
        }
    });
}

/* MongoDb Update User Password*/
exports.updatePassword = function (email, newPass, callback)
{
    accounts.findOne({email: email}, function (e, o) {
        if (e) {
            callback(e, null);
        } else {
            saltAndHash(newPass, function (hash) {
                o.password = hash;
                accounts.save(o, {safe: true}, callback);
            });
        }
    });
}

/* MongoDb Delete User record*/
exports.deleteAccount = function (id, callback)
{
    accounts.remove({_id: getObjectId(id)}, callback);
}

/* Get user details using Email ID */
exports.getAccountByEmail = function (email, callback)
{
    accounts.findOne({email: email}, function (e, o) {
        callback(o);
    });
}

/* Get All User data */
exports.getAllRecords = function (callback)
{
    accounts.find().toArray(
            function (e, res) {
                if (e) {
                    callback(e)
                } else {
                    console.log(res);
                    callback(null, res)
                }
            });

}

/* Delete All Data of User */
exports.delAllRecords = function (callback)
{
    accounts.remove({}, callback); // reset accounts collection for testing //
}

/* Password encryption cutomize method */
var generateSalt = function ()
{
    var set = '0123456789abcdefghijklmnopqurstuvwxyzABCDEFGHIJKLMNOPQURSTUVWXYZ';
    var salt = '';
    for (var i = 0; i < 10; i++) {
        var p = Math.floor(Math.random() * set.length);
        salt += set[p];
    }
    return salt;
}

var md5 = function (str) {
    return crypto.createHash('md5').update(str).digest('hex');
}

var saltAndHash = function (password, callback)
{
    var salt = generateSalt();
    callback(salt + md5(password + salt));
}
/* Validate Password */
var validatePassword = function (plainPass, hashedPass, callback)
{
    var salt = hashedPass.substr(0, 10);
    var validHash = salt + md5(plainPass + salt);
    callback(null, hashedPass === validHash);
}

var getObjectId = function (id)
{
    return new require('mongodb').ObjectID(id);
}

// Find user record using User ID
var findById = function (id, callback)
{
    accounts.findOne({_id: getObjectId(id)},
            function (e, res) {
                if (e)
                    callback(e)
                else
                    callback(null, res)
            });
}

// Find User record using multiple fields.
var findByMultipleFields = function (a, callback)
{
    // this takes an array of name/val pairs to search against {fieldName : 'value'} //
    accounts.find({$or: a}).toArray(
            function (e, results) {
                if (e)
                    callback(e)
                else
                    callback(null, results)
            });
}
