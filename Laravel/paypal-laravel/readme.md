# Paypal IPN in Laravel 5.3

This is a very simple and easy demo to integrate Paypal IPN method in laravel 5.3, which is mainly for beginners.


## Features ##

- Display product at home page
- When press pay button then paypal payment will be done.


## Installation & Setup ##

1. Install Laravel using https://github.com/laravel/laravel

2. Download or clone of this repository.

3. Add files to related folders. 

    - Copy app/Product.php file to app/ folder 

    - Copy app/Http/Controllers/PaypalController.php to app/Http/Controllers/ folder

    - Copy config/paypal.php to config/ folder
 
    - Copy public/images folder to public/

    - Copy resources/layouts folder to resources/

    - Copy resources/product folder to resources/


4. Open routes/web.php and paste following code.


    Route::resource('/','PaypalController');

    Route::post('afterpayment', ['uses' => 'PaypalController@afterPayment']);

    Route::get('cancelpayment', ['uses' => 'PaypalController@cancelPayment']);

    Route::get('successpayment', ['uses' => 'PaypalController@successPayment']);


		
5. Open a browser window and navigate to: [http://localhost/Your project name]


## Contributing ##

Questions and suggestions for improvement are welcome.