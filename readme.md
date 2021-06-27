# Behfarda.com Laravel

Laravel package to connect to behfarda.com Payment Gateway

## Installation

`composer require sabanovin/behfarda`

## Publish Configurations

`php artisan vendor:publish --provider="SabaNovin\Behfarda\BehfardaServiceProvider"`

## Config

Set your api key and redirect url in `.env` file:

    BEHFARDA_MERCHANT_ID=test
    BEHFARDA_CALLBACK_URL=/behafarda/callback
    
## Usage

### Payment Controller

    <?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use SabaNovin\Behfarda\Exceptions\SendException;
    use SabaNovin\Behfarda\Exceptions\VerifyException;
    use SabaNovin\Behfarda\BehfardaPG;
    
    class PaymentController extends Controller
    {
        public function pay()
        {
            $behfarda = new BehfardaPG();
            $behfarda->amount = 100; // Required, Amount
            $behfarda->client_id = 'Client-ID'; // Optional
            $behfarda->description = 'Some Description'; // Optional
            $behfarda->mobile = '0912XXXXXXX'; // Optional, If you want to show user's saved card numbers in gateway
            $behfarda->valid_card_number = '6037990000000000'; // Optional, If you want to limit the payable card
    
            try {
                $behfarda->send();
    
                return redirect($behfarda->payment_url);
            } catch (SendException $e) {
                throw $e;
            }
        }
    
        public function verify(Request $request)
        {
            $behfarda = new BehfardaPG();
            $behfarda->token = $request->token; // behfarda.com returns this token to your redirect url
    
            try {
                $verify = $behfarda->verify(); // returns verify result from behfarda.com like (transId, cardNumber, ...)
    
                dd($verify);
            } catch (VerifyException $e) {
                throw $e;
            }
        }
    }

### Routes

    Route::get('/behfarda/callback', 'PaymentController@verify');
    
## Usage with facade

Config `aliases` in `config/app.php` :

    'Behfarda' => SabaNovin\Behfarda\Facades\Behfarda::class
    
*Send*

    Behfarda::send($amount, $redirect = null, $factorNumber = null, $mobile = null, $description = null);
    
*SendArray*

Alternatively, You can use `sendArray` method in facade to send optional data to Behfarda.com

    Behfarda::sendArray([
      'merchant_id' => 'Merchant-ID', // optional, If you don't send this the package will read this from env
      'amount' => 100,
      'callback_url' => 'Your-Redirect-Url', // optional
      'client_id' => 'Client-ID', // optional
      'mobile' => 'Mobile-Number', // optional
      'description' => 'Your-Description', // optional
      'valid_card_number' => 'Valid-Card-Number' // optional
    ]);

*Verify*

    Behfarda::verify($token);

## Security

If you discover any security related issues, please create an issue or email me (h.kamrava1@gmail.com)
    
## License

This repo is open-sourced software licensed under the MIT license.
