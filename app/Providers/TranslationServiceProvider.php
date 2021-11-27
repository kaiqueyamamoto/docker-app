<?php 

namespace App\Providers; 

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\ServiceProvider; 

class TranslationServiceProvider extends ServiceProvider 
{ 
    /** 
     * The path to the current lang files. 
     * 
     * @var string 
     */ 
    protected $langPath; 

    /** 
     * Create a new service provider instance. 
     * 
     * @return void 
     */ 
    public function __construct() 
    { 
        $this->langPath = resource_path('lang/'.App::getLocale());
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $availableLanguagesENV = ENV('FRONT_LANGUAGES', 'EN,English,IT,Italian,FR,French,DE,German,ES,Spanish,RU,Russian,PT,Portuguese,TR,Turkish');
        $exploded = explode(',', $availableLanguagesENV);
        for ($i = 0; $i < count($exploded); $i += 2) {
            $lng=strtolower($exploded[$i]);
            try {
                Cache::rememberForever('translations'.$lng, function () use ($lng) {
                    return collect(File::allFiles(resource_path('lang/'.$lng)))->flatMap(function ($file) {
                        if($file->getBasename('.php')=="custom"){
                            return [
                                ($translation = $file->getBasename('.php')) => trans($translation),
                            ];
                        }
                        
                    })->toJson();
                });
            } catch (\Throwable $th) {
                //throw $th;
            }
            
        }

        //dd('translations'.App::getLocale());
        
    }
}