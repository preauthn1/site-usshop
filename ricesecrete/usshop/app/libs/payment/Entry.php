<?php

namespace app\libs\payment;

class Entry implements PaymentInterface
{
    private array $config;

    public function __construct($config)
    {
        if (is_string($config)) {
            $config=json_decode($config,true);
        }
        $this->config=$config;
    }
    public function create($packageName,$input)
    {
        $obj= "\app\libs\payment\\".$packageName;
        return (new $obj($this->config))->create($packageName, $input);
    }

    public function withdrawal()
    {
        // TODO: Implement withdrawal() method.
    }

    public function getOrder()
    {
        // TODO: Implement getOrder() method.
    }

    public function notify($packageName, $input)
    {
        if(!$input){
            $input = file_get_contents("php://input");
            $input = json_decode($input,true);
        }

        $obj= "\app\libs\payment\\".$packageName;
        return (new $obj($this->config))->notify($packageName, $input);
    }

    public function notify_withdrawal($packageName, $input)
    {
        // TODO: Implement notify_withdrawal() method.
    }

}
