<?php

namespace App\Traits;
use Akaunting\Module\Facade as Module;

trait Modules
{


    private function vendorFields($configs){
        $fields=[];
        foreach (Module::all() as $key => $module) {
            if(is_array($module->get('vendor_fields'))){

                $insertThisModuleVendorFields=true;
                //If Module is payment method, check if admin has approved usage
                if($module->get('isPaymentModule')){
                    //check if admin has approved usage
                    if(!config($module->get('alias').".useVendor")){
                        $insertThisModuleVendorFields=false;
                    }

                }

                if($insertThisModuleVendorFields){
                    foreach ($module->get('vendor_fields') as $key => $field) {
                        if($field['key']=="time_zone"){
                            $field['data']=config('timezones');
                        }
                        if (! (isset($field['onlyin']) && $field['onlyin'] != config('settings.app_project_type'))) {
                            
                            array_push($fields,$field);
                        }
                    }
                }
                
            }
            
            //$fields=array_merge($fields,$module->get('vendor_fields'));
            //Add active field
            /*array_push($fields,[
                "title" => "Active",
                "key" => $key."_active",
                "ftype" => "bool",
                "value" => false]
            );*/
        }

        

        foreach ($fields as &$field) {
           if(isset($configs[$field['key']])){
            $field['value']=$configs[$field['key']];
           }else if(!isset($field['value'])){
            $field['value']="";
           }
        }
        return $fields;

       
        
    }
}
    