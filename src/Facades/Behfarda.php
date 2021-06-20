<?php

declare(strict_types=1);

namespace SabaNovin\Behfarda\Facades;

use Illuminate\Support\Facades\Facade;
use SabaNovin\Behfarda\Exceptions\SendException;
use SabaNovin\Behfarda\Exceptions\VerifyException;
use SabaNovin\Behfarda\Http\Request;

/**
 * This is the behfarda facade class.
 */
class Behfarda extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'behfarda';
    }

    /**
     * Send data to behfarda.com and init transaction
     *
     * @param $amount
     * @param $callback_url
     * @param null $factorNumber
     * @param null $mobile
     * @param null $description
     * @return mixed
     * @throws SendException
     */
    public static function send($amount, $callback_url = null, $factorNumber = null, $mobile = null, $description = null, $api = null, $validCardNumber = null)
    {
        $data = [
            'merchant_id' => $api ? $api : config('behfarda.merchant_id'),
            'callback_url' => $callback_url ? $callback_url : url(config('behfarda.callback_url')),
            'amount' => $amount,
            'factorNumber' => $factorNumber,
            'mobile' => $mobile,
            'description' => $description,
            'resellerId' => '1000000012'
        ];
        if ($validCardNumber) {
            $data['validCardNumber'] = $validCardNumber;
        }
        $send = Request::make('https://behfarda.com/payment/request', $data);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200) {
                $send['response']['data']['payment_url'] = 'https://behfarda.com/pg/' . $send['response']['data']['token'];

                return $send['response']['data'];
            }

            throw new SendException($send['response']['errors']);
        }

        throw new SendException('خطا در ارسال اطلاعات به behfarda.com. لطفا از برقرار بودن اینترنت و در دسترس بودن behfarda.com اطمینان حاصل کنید');
    }

    /**
     * Send data to behfarda.com and init transaction with options
     *
     * @param array $options
     * @return mixed
     * @throws SendException
     */
    public static function sendArray(array $options)
    {
        if (!isset($options['merchant_id'])) {
            $options['merchant_id'] = config('behfarda.merchant_id');
        }
        if (!isset($options['callback_url'])) {
            $options['callback_url'] = url(config('behfarda.callback_url'));
        }
        $options['resellerId'] = '1000000012';
        $send = Request::make('https://behfarda.com/payment/request', $options);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200) {
                $send['response']['data']['payment_url'] = 'https://behfarda.com/pg/' . $send['response']['data']['token'];

                return $send['response']['data'];
            }

            throw new SendException($send['response']['errors']);
        }

        throw new SendException('خطا در ارسال اطلاعات به behfarda.com. لطفا از برقرار بودن اینترنت و در دسترس بودن behfarda.com اطمینان حاصل کنید');
    }

    /**
     * Verify transaction
     *
     * @param $token
     * @return mixed
     * @throws VerifyException
     */
    public static function verify($token, $api = null)
    {
        $verify = Request::make('https://behfarda.com/pg/verify', [
            'merchant_id' => $api ? $api : config('behfarda.merchant_id'),
            'token' => $token,
        ]);
        if (isset($verify['status']) && isset($verify['response'])) {
            if ($verify['status'] == 200) {
                return $verify['response'];
            }

            throw new VerifyException($verify['response']['message']);
        }

        throw new VerifyException('خطا در ارسال اطلاعات به behfarda.com. لطفا از برقرار بودن اینترنت و در دسترس بودن behfarda.com اطمینان حاصل کنید');
    }
}
