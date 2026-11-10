<?php

namespace app\libs\payment;

interface PaymentInterface
{
    public function __construct($config);
    public function create($packageName,$input);
    public function withdrawal();
    public function getOrder();
    public function notify($packageName, $input);
    public function notify_withdrawal($packageName, $input);
}
