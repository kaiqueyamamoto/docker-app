<?php

namespace App\Traits\Payments;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Akaunting\Module\Facade as Module;

trait HasLinkPayment
{
    public function paymentRules(){
        return [];
    }

    public function payOrder(){

        $className = '\Modules\\'.ucfirst($this->request->payment_method).'\Http\Controllers\App';
        $ref = new \ReflectionClass($className);
        $theValidator = $ref->newInstanceArgs(array($this->order,$this->vendor))->execute();
        if($theValidator->fails()){
            $this->invalidateOrder();
        }else{
            //It is ok, use the link
            $this->paymentRedirect= $this->order->payment_link;
        }
        return $theValidator;
    }
}
