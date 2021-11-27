<?php

namespace App\Repositories\Orders;

class OrderRepoGenerator extends BaseOrderRepository
{

   
    public static function makeOrderRepo($vendor_id,$request,$expedition,$hasPayment,$isStripe,$isMobile=false,$vendorHasOwnPayment=null){

        //Find the type
        $serviceType=$isMobile?"MobileApp":"WebService"; //FT
        if(config('app.isqrsaas')){
            if(config('settings.is_whatsapp_ordering_mode') ||config('settings.is_facebook_ordering_mode')){
                $serviceType="Social";//Whatsapp and FB

                //In Social we have chargin directly by restaurant
                if($vendorHasOwnPayment!=null){
                    $hasPayment=true;
                    $request->payment_method=$vendorHasOwnPayment;
                }
            }else if(config('settings.is_pos_cloud_mode')){
                $serviceType="POS";//POS
            }else{
                $serviceType="Local";//QR
            }
        }

        //Expedition
        $expeditionType="Delivery";
        if($expedition=="pickup"){
            $expeditionType="Pickup";
        }else if($expedition=="dinein"){
            $expeditionType="Dinein";
        }

        //Payment
        $paymentType="COD";
        if($hasPayment){
            if($isStripe){
                $paymentType="Stripe";
            }else {
                $paymentType="LinkPayment";
            }
        }

        //Class
        $generatedClass='App\Repositories\Orders\\'.$serviceType."\\".$expeditionType.$paymentType."Order";
        return new $generatedClass($vendor_id,$request,$expedition,$hasPayment,$isStripe);
    }
}