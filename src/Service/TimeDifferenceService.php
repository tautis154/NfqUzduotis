<?php

namespace App\Service;

use DateTime;
use Exception;

class TimeDifferenceService
{
    public function TimeDifferenceCalculator($customers)
    {
        $customerAppointmentTime = $customers[0]->getAppointmentTime();
        $customerTimeLeft = null;
        $now = date('Y-m-d h:i:s', time());
        try {
            $x = new DateTime($now);
        } catch (Exception $e) {
        }
        $x = $x->diff($customerAppointmentTime);

        if (1 === $x->invert) {
            $customerTimeLeft = 0;
        }
        else{
            $customerTimeLeft = $x->format("%Y Years %M Months %D Days %H:%I.%S");
        }
        return $customerTimeLeft;
    }
}
