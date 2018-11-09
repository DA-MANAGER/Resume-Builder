<?php

namespace App\Store;

use KingFlamez\Rave\Rave;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class LaravelRave extends Rave {
    /**
     * Extracts the plan details out of the response.
     * 
     * @param  Object $plan
     * 
     * @return object|null
     */
    public function parsePaymentResponse($plan) {
        //check the status is success
        if (is_object($plan) && $plan->status === "success") {
            if (property_exists($plan, "data")) {
                if (count($plan->data->paymentplans) === 1) {
                    return $plan->data->paymentplans[0];
                }

                return $plan->data->paymentplans;
            }
        }

        return null;
    }

    /**
     * Handle canceled payments with this method
     *
     * @param string $referenceNumber This should be the reference number of the transaction that was canceled.
     *
     * @return mixed
     * */
    public function paymentCanceled($referenceNumber, $data)
    {
        $this->txref = $referenceNumber;
        
        if (request()->cancelled) {
            $cancelledResponse = '{"status": "cancelled" , "message": "Customer cancelled the transaction", "data":{ "status": "cancelled", "txRef" :"' . $this->txref . '"}}';
            $resp = json_decode($cancelledResponse);

            return $resp;
        } else {
            return $data;
        }
    }
}
