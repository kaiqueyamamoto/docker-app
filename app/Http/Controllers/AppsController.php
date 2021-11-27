<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Akaunting\Module\Facade as Module;
use ZipArchive;

class AppsController extends Controller
{
    public function index(){
        //1. Get all available apps
        $appsLink="https://raw.githubusercontent.com/mobidonia/foodtigerapps/main/apps24.json";
        $response = (new \GuzzleHttp\Client())->get($appsLink);

        $apps=[];
        if ($response->getStatusCode() == 200) {
            $apps = json_decode($response->getBody());
        }

        //2. Merge info
        foreach ($apps as $key => &$app) {
            $app->installed=Module::has($app->alias);
            if($app->installed){
                $app->version=Module::get($app->alias)->get('version');
            }
        }
        

        //3. Return view
        return view('apps.index',compact('apps'));

    }

    public function store(Request $request){
       
        $path=$request->appupload->storeAs('appupload', $request->appupload->getClientOriginalName());
        
        $fullPath = storage_path('app/'.$path);
        $zip = new ZipArchive;

        if ($zip->open($fullPath)) {

            //Modules folder
            $destination=public_path('../modules');
            // Extract file
            $zip->extractTo($destination);
            
            // Close ZipArchive     
            $zip->close();
            return redirect()->route('apps.index')->withStatus(__('App is installed'));
        }else{
            return redirect(route('apps.index'))->withError(__('There was an error on app install. Please try manual install'));
        }
    }
}
