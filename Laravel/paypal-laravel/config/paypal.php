<?php
return array(
    // set your paypal credential
    'paypal_url'=>'https://www.sandbox.paypal.com/cgi-bin/webscr', // Sandbox or Live paypal URL
    'rm' => '1',/*Return method. The FORM METHOD used to send data to the URL specified by the return variable. 

                    0 — all shopping cart payments use the GET method
                    1 — the buyer's browser is redirected to the return URL by using the GET method, but no payment variables are included
                    2 — the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included

                The default is 0.      */
    'no_shipping' => '',/*Do not prompt buyers for a shipping address. 
                        Do not prompt buyers for a shipping address.

                        Allowable values are:

                            0 — prompt for an address, but do not require one
                            1 — do not prompt for an address
                            2 — prompt for an address, and require one

                        The default is 0.      */
    'no_note' => '1', /*Do not prompt buyers to include a note with their payments. Allowable values are: 
                      * 
                        0 — provide a text box and prompt for the note
                        1 — hide the text box and the prompt
                      *  */
    'currency_code' => 'USD', //The currency of the payment. The default is USD. 
    'page_style' => 'paypal', /*The custom payment page style for checkout pages. Allowable values are:

                            paypal — use the PayPal page style
                            primary — use the page style that you marked as primary in your account profile
                            page_style_name — use the custom payment page style from your account profile that has the specified name

                        The default is primary if you added a custom payment page style to your account profile. Otherwise, the default is paypal.
                        
                          */
    'charset' => 'utf-8',/* Sets the character set and character encoding for the billing information/log-in page on the PayPal website. In addition, this variable sets the same values for information that you send to PayPal in your HTML button code. The default is based on the language encoding settings in your Account Profile. */
    
    'business' => 'BUSINESS_EMAIL_ID',/*Your PayPal ID or an email address associated with your PayPal account. Email addresses must be confirmed. */
    
    
    
);