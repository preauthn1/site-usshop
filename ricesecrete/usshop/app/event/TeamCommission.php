<?php
declare (strict_types = 1);

namespace app\event;

class TeamCommission
{
    public int $user_id;
    public float $amount;
    public function __construct($user_id, $amount)
    {
        $this->user_id = $user_id;
        $this->amount = $amount;
    }
}
