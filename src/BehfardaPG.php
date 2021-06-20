<?php

namespace SabaNovin\Behfarda;

use SabaNovin\Behfarda\Facades\Behfarda;

class BehfardaPG
{
    public $token;
    public $amount;
    public $redirect;
    public $client_id;
    public $mobile;
    public $description;
    public $payment_url;
    public $valid_card_number;

    /**
     * send
     *
     * @return mixed
     * @throws Exceptions\SendException
     */
    public function send()
    {
        try {
            $send = Behfarda::send($this->amount, $this->redirect, $this->client_id, $this->mobile, $this->description, $this->valid_card_number);

            $this->token = $send['token'];
            $this->payment_url = $send['payment_url'];
        } catch (Exceptions\SendException $e) {
            throw $e;
        }
    }

    /**
     * verify
     *
     * @return mixed
     * @throws Exceptions\VerifyException
     */
    public function verify()
    {
        try {
            return Behfarda::verify($this->token);
        } catch (Exceptions\VerifyException $e) {
            throw $e;
        }
    }
}