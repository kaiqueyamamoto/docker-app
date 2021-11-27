<?php

namespace App\Http\Controllers;

use App\Categories;
use App\City;
use App\Events\CallWaiter;
use App\Extras;
use App\Hours;
use App\Imports\RestoImport;
use App\Items;
use App\Models\LocalMenu;
use App\Models\Options;
use App\Notifications\RestaurantCreated;
use App\Notifications\WelcomeNotification;
use App\Plans;
use App\Restorant;
use App\Tables;
use App\User;
use App\Traits\Fields;
use App\Traits\Modules;
use Artisan;
use Carbon\Carbon;
use DB;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
//use Intervention\Image\Image;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Geocoder\Geocoder;

class RestorantController extends Controller
{
    use Fields;
    use Modules;
    
    protected $imagePath = 'uploads/restorants/';

    /**
     * Auth checker functin for the crud.
     */
    private function authChecker()
    {
        $this->ownerOnly();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Restorant $restaurants)
    {
        if (auth()->user()->hasRole('admin')) {
            //return view('restorants.index', ['restorants' => $restaurants->where(['active'=>1])->paginate(10)]);
            return view('restorants.index', ['restorants' => $restaurants->orderBy('id', 'desc')->paginate(10)]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    public function loginas(Restorant $restaurant)
    {
        if (auth()->user()->hasRole('admin')) {
            //Login as owner
            Auth::login($restaurant->user, true);

            return $this->edit($restaurant);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->hasRole('admin')) {
            return view('restorants.create');
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate first
        $request->validate([
            'name' => ['required', 'string', 'unique:companies,name', 'max:255'],
            'name_owner' => ['required', 'string', 'max:255'],
            'email_owner' => ['required', 'string', 'email', 'unique:users,email,NULL,id,deleted_at,NULL', 'max:255'],
            'phone_owner' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
        ]);

        //Create the user
        $generatedPassword = Str::random(10);
        $owner = new User;
        $owner->name = strip_tags($request->name_owner);
        $owner->email = strip_tags($request->email_owner);
        $owner->phone = strip_tags($request->phone_owner) | '';
        $owner->api_token = Str::random(80);

        $owner->password = Hash::make($generatedPassword);
        $owner->save();

        //Assign role
        $owner->assignRole('owner');

        //Create Restorant
        $restaurant = new Restorant;
        $restaurant->name = strip_tags($request->name);
        $restaurant->user_id = $owner->id;
        $restaurant->description = strip_tags($request->description.'');
        $restaurant->minimum = $request->minimum | 0;
        $restaurant->lat = 0;
        $restaurant->lng = 0;
        $restaurant->address = '';
        $restaurant->phone = $owner->phone;
        $restaurant->subdomain = $this->makeAlias(strip_tags($request->name));
        //$restaurant->logo = "";
        $restaurant->save();

       //default hours
       $hours = new Hours();
       $hours->restorant_id = $restaurant->id;

       $shift="_shift".$request->shift_id;
       
       $hours->{'0_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'0_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       $hours->{'1_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'1_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       $hours->{'2_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'2_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       $hours->{'3_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'3_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       $hours->{'4_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'4_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       $hours->{'5_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'5_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       $hours->{'6_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
       $hours->{'6_to'} = config('settings.time_format') == "AM/PM" ? "5:00 PM" : "17:00";
       
       $hours->save();

        $restaurant->setConfig('disable_callwaiter', 0);

        //Send email to the user/owner
        $owner->notify(new RestaurantCreated($generatedPassword, $restaurant, $owner));

        return redirect()->route('admin.restaurants.index')->withStatus(__('Restaurant successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Restorant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restorant $restaurant)
    {
        //
    }


    public function addnewshift(Restorant $restaurant){
        if (auth()->user()->id == $restaurant->user_id || auth()->user()->hasRole('admin')) {
            $hours = new Hours();
            $hours->restorant_id = $restaurant->id;
            $hours->save();
            return redirect()->route('admin.restaurants.edit', ['restaurant' => $restaurant->id])->withStatus(__('New shift added!'));
        }else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Restorant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restorant $restaurant)
    {
       
        //dd($restaurant->getBusinessHours()->isOpen());

        //Days of the week
        $timestamp = strtotime('next Monday');
        for ($i = 0; $i < 7; $i++) {
            $days[] = strftime('%A', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        //Generate days columns
        $hoursRange = ['id'];
        for ($i = 0; $i < 7; $i++) {
            $from = $i.'_from';
            $to = $i.'_to';

            array_push($hoursRange, $from);
            array_push($hoursRange, $to);
        }

        

        

        //Languages
        $available_languages=[];
        $default_language=null;

        try {
            if($restaurant->localMenus()->get()){
                $available_languages=$restaurant->localMenus()->get()->pluck('languageName','id');
                foreach ($restaurant->localMenus()->get() as $key => $localMenu) {
                    if($localMenu->default.""=="1"){
                        $default_language= $localMenu->id;
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        

        //currency
        if(strlen($restaurant->currency)>1){
            $currency= $restaurant->currency;
        }else{
            $currency=config('settings.cashier_currency');
        }

        //App fields
        $rawFields=$this->vendorFields($restaurant->getAllConfigs());
        //Stripe fields
        if(config('settings.stripe_useVendor')){
            array_push($rawFields,[
                "separator"=>"Stripe configuration",
                "title"=> "Enable Stripe for payments when ordering",
                "key"=> "stripe_enable",
                "ftype"=> "bool",
                "value"=>$restaurant->getConfig('stripe_enable',"false"),
                "onlyin"=>"qrsaas"
            ],[
                "title"=>"Stripe key", 
                "key"=>"stripe_key", 
                "value"=>$restaurant->getConfig('stripe_key',""),
                "onlyin"=>"qrsaas"
            ],
            [
                "title"=>"Stripe secret", 
                "key"=>"stripe_secret", 
                "value"=>$restaurant->getConfig('stripe_secret',""),
                "onlyin"=>"qrsaas"
            ]);
        }
  
       
        $appFields=$this->convertJSONToFields($rawFields);

        $shiftsData = Hours::where(['restorant_id' => $restaurant->id])->get($hoursRange);
        $shifts=[];
        foreach ($shiftsData as $key => $hours) {
            $shiftId=$hours->id;
            $workingHours=$hours->toArray();
            unset($workingHours['id']);
            $shifts[$shiftId]=$workingHours;
        }

        if (auth()->user()->id == $restaurant->user_id || auth()->user()->hasRole('admin')) {
            //return view('restorants.edit', compact('restorant'));
            $cities=[];
            try {
                $cities=City::get()->pluck('name', 'id');
            } catch (\Throwable $th) {
                //throw $th;
            }
            return view('restorants.edit', [
                'restorant' => $restaurant,
                'shifts'=>$shifts,
                'days'=>$days,
                'cities'=> $cities,
                'plans'=>Plans::get()->pluck('name', 'id'),
                'available_languages'=> $available_languages,
                'default_language'=>$default_language,
                'currency'=>$currency,
                'appFields'=>$appFields
                ]);
        }

        return redirect()->route('home')->withStatus(__('No Access'));
    }

    public function updateApps(Request $request, Restorant $restaurant){
         //Update custom fields
         if($request->has('custom')){
            $restaurant->setMultipleConfig($request->custom);
        }
        return redirect()->route('admin.restaurants.edit', ['restaurant' => $restaurant->id])->withStatus(__('Restaurant successfully updated.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Restorant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restorant $restaurant)
    {
        $restaurant->name = strip_tags($request->name);
        $restaurant->address = strip_tags($request->address);
        $restaurant->phone = strip_tags($request->phone);
        
        $restaurant->description = strip_tags($request->description);
        $restaurant->minimum = strip_tags($request->minimum);

        if($request->fee){
            $restaurant->fee = $request->fee;
            $restaurant->static_fee = $request->static_fee;
        }
    
        $restaurant->subdomain = $this->makeAlias(strip_tags($request->name));
        $restaurant->is_featured = $request->is_featured != null ? 1 : 0;
        $restaurant->can_pickup = $request->can_pickup == 'true' ? 1 : 0;
        $restaurant->can_deliver = $request->can_deliver == 'true' ? 1 : 0;
        $restaurant->self_deliver = $request->self_deliver == 'true' ? 1 : 0;
        $restaurant->free_deliver = $request->free_deliver == 'true' ? 1 : 0;

        if($request->has('disable_callwaiter')){
            $restaurant->setConfig('disable_callwaiter',$request->disable_callwaiter == 'true' ? 1 : 0);
        }

        if($request->has('payment_info')){
            $restaurant->payment_info = $request->payment_info;
        }

        if($request->has('whatsapp_phone')){
            $restaurant->whatsapp_phone = $request->whatsapp_phone;
        }

        if (isset($request->city_id)) {
            $restaurant->city_id = $request->city_id;
        }

        //dd($request->all());

        if ($request->hasFile('resto_logo')) {
            $restaurant->logo = $this->saveImageVersions(
                $this->imagePath,
                $request->resto_logo,
                [
                    ['name'=>'large', 'w'=>590, 'h'=>400],
                    ['name'=>'medium', 'w'=>295, 'h'=>200],
                    ['name'=>'thumbnail', 'w'=>200, 'h'=>200],
                ]
            );
        }

        if ($request->hasFile('resto_wide_logo')) {
       
            $uuid = Str::uuid()->toString();
            $request->resto_wide_logo->move(public_path($this->imagePath), $uuid.'_original.'.'png');
            $restaurant->setConfig('resto_wide_logo',$uuid);
        }

        if ($request->hasFile('resto_wide_logo_dark')) {
       
            $uuid = Str::uuid()->toString();
            $request->resto_wide_logo_dark->move(public_path($this->imagePath), $uuid.'_original.'.'png');
            $restaurant->setConfig('resto_wide_logo_dark',$uuid);
        }

        
        if ($request->hasFile('resto_cover')) {
            $restaurant->cover = $this->saveImageVersions(
                $this->imagePath,
                $request->resto_cover,
                [
                    ['name'=>'cover', 'w'=>2000, 'h'=>1000],
                    ['name'=>'thumbnail', 'w'=>400, 'h'=>200],
                ]
            );
        }

        //Change default language
        //If language is different than the current one
        if($request->default_language){
            $currentDefault=$restaurant->localMenus()->where('default',1)->first();
            if($currentDefault!=null&&$currentDefault->id!=$request->default_language){
                //Remove Default from the old default, or curernt default
                $currentDefault->default=0;
                $currentDefault->update();
            }

            //Make the new language default
            $newDefault=$restaurant->localMenus()->findOrFail($request->default_language);
            $newDefault->default=1;
            $newDefault->update();
        }
        

        //Change currency
        $restaurant->currency=$request->currency;

        //Change do converstion
        $restaurant->do_covertion=$request->do_covertion=="true"?1:0;

        $restaurant->update();


        //Update custom fields
        if($request->has('custom')){
            $restaurant->setMultipleConfig($request->custom);
        }

        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.restaurants.edit', ['restaurant' => $restaurant->id])->withStatus(__('Restaurant successfully updated.'));
        } else {
            return redirect()->route('admin.restaurants.edit', ['restaurant' => $restaurant->id])->withStatus(__('Restaurant successfully updated.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Restorant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restorant $restaurant)
    {
        if (! auth()->user()->hasRole('admin')) {
            dd('Not allowed');
        }

        $restaurant->active = 0;
        $restaurant->save();

        //$restaurant->delete();

        return redirect()->route('admin.restaurants.index')->withStatus(__('Restaurant successfully deactivated.'));
    }

    public function remove(Restorant $restaurant)
    {
        if (! auth()->user()->hasRole('admin')) {
            dd('Not allowed');
        }

        $user = $restaurant->user;

        //delete restaurant
        $user->restorant->delete();

        if ($user->email != 'owner@example.com') {
            //delete user
            $user->delete();
        }

        return redirect()->route('admin.restaurants.index')->withStatus(__('Restaurant successfully removed from database.'));
    }

    public function getCoordinatesForAddress(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));

        $geoResults = $geocoder->getCoordinatesForAddress($request->address);

        $data = ['status'=>true, 'results'=>$geoResults];

        return response()->json($data);
    }

    public function updateLocation(Restorant $restaurant, Request $request)
    {
        $restaurant->lat = $request->lat;
        $restaurant->lng = $request->lng;

        $restaurant->update();

        return response()->json([
            'status' => true,
            'errMsg' => '',
        ]);
    }

    public function updateRadius(Restorant $restaurant, Request $request)
    {
        $restaurant->radius = $request->radius;
        $restaurant->update();

        return response()->json([
            'status' => true,
            'msg' => '',
        ]);
    }

    public function updateDeliveryArea(Restorant $restaurant, Request $request)
    {
        $restaurant->radius = json_decode($request->path);
        $restaurant->update();

        return response()->json([
            'status' => true,
            'msg' => '',
        ]);
    }

    public function getLocation(Restorant $restaurant)
    {
        return response()->json([
            'data' => [
                'lat' => $restaurant->lat,
                'lng' => $restaurant->lng,
                'area' => $restaurant->radius,
                'id' => $restaurant->id,
            ],
            'status' => true,
            'errMsg' => '',
        ]);
    }

    public function import(Request $request)
    {
        Excel::import(new RestoImport, request()->file('resto_excel'));

        return redirect()->route('admin.restaurants.index')->withStatus(__('Restaurant successfully imported.'));
    }

    public function workingHoursremove(Hours $hours){
        if (!auth()->user()->hasRole('admin')) {
            if (auth()->user()->hasRole('owner')&&$hours->restorant->user->id==auth()->user()->id) {
                //ok, owner
            }else{
                abort(404);
            }
           
        }


        $hours->delete();
        return redirect()->route('admin.restaurants.edit', ['restaurant' => $hours->restorant_id])->withStatus(__('Working hours successfully updated!'));
    }

    public function workingHours(Request $request)
    {
        $hours = Hours::where(['id' => $request->shift_id])->first();

        $shift="_shift".$request->shift_id;
        
        $hours->{'0_from'} = $request->{'0_from'.$shift} ?? null;
        $hours->{'0_to'} = $request->{'0_to'.$shift} ?? null;
        $hours->{'1_from'} = $request->{'1_from'.$shift} ?? null;
        $hours->{'1_to'} = $request->{'1_to'.$shift} ?? null;
        $hours->{'2_from'} = $request->{'2_from'.$shift} ?? null;
        $hours->{'2_to'} = $request->{'2_to'.$shift} ?? null;
        $hours->{'3_from'} = $request->{'3_from'.$shift} ?? null;
        $hours->{'3_to'} = $request->{'3_to'.$shift} ?? null;
        $hours->{'4_from'} = $request->{'4_from'.$shift} ?? null;
        $hours->{'4_to'} = $request->{'4_to'.$shift} ?? null;
        $hours->{'5_from'} = $request->{'5_from'.$shift} ?? null;
        $hours->{'5_to'} = $request->{'5_to'.$shift} ?? null;
        $hours->{'6_from'} = $request->{'6_from'.$shift} ?? null;
        $hours->{'6_to'} = $request->{'6_to'.$shift} ?? null;
        $hours->update();

        return redirect()->route('admin.restaurants.edit', ['restaurant' => $request->rid])->withStatus(__('Working hours successfully updated!'));
    }

    public function showRegisterRestaurant()
    {
        return view('restorants.register');
    }

    public function storeRegisterRestaurant(Request $request)
    {
        //Validate first
        $theRules = [
            'name' => ['required', 'string', 'unique:companies,name', 'max:255'],
            'name_owner' => ['required', 'string', 'max:255'],
            'email_owner' => ['required', 'string', 'email', 'unique:users,email,NULL,id,deleted_at,NULL', 'max:255'],
            'phone_owner' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
        ];

        if (strlen(config('settings.recaptcha_site_key')) > 2) {
            $theRules['g-recaptcha-response'] = 'recaptcha';
        }

        $request->validate($theRules);

        //Create the user
        //$generatedPassword = Str::random(10);
        $owner = new User;
        $owner->name = strip_tags($request->name_owner);
        $owner->email = strip_tags($request->email_owner);
        $owner->phone = strip_tags($request->phone_owner) | '';
        $owner->active = 0;
        $owner->api_token = Str::random(80);

        $owner->password = null;
        $owner->save();

        //Assign role
        $owner->assignRole('owner');

        //Send welcome email

        //welcome notification
        //Create Restorant
        $restaurant = new Restorant;
        $restaurant->name = strip_tags($request->name);
        $restaurant->user_id = $owner->id;
        $restaurant->description = strip_tags($request->description.'');
        $restaurant->minimum = $request->minimum | 0;
        $restaurant->lat = 0;
        $restaurant->lng = 0;
        $restaurant->address = '';
        $restaurant->phone = $owner->phone;
        //$restaurant->subdomain=strtolower(preg_replace('/[^A-Za-z0-9]/', '', strip_tags($request->name)));
        $restaurant->active = 0;
        $restaurant->subdomain = null;
        //$restaurant->logo = "";
        $restaurant->save();

        //default hours
        $hours = new Hours();
        $hours->restorant_id = $restaurant->id;

        $shift="_shift".$request->shift_id;
        
        $hours->{'0_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'0_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        $hours->{'1_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'1_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        $hours->{'2_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'2_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        $hours->{'3_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'3_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        $hours->{'4_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'4_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        $hours->{'5_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'5_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        $hours->{'6_from'} = config('settings.time_format') == "AM/PM" ? "9:00 AM" : "09:00";
        $hours->{'6_to'} = config('settings.time_format') == "AM/PM" ? "5:00 AM" : "17:00";
        
        $hours->save();

        $restaurant->setConfig('disable_callwaiter', 0);

        if (config('app.isqrsaas') || config('settings.directly_approve_resstaurant')) {
            //QR SaaS - or directly approve
            $this->makeRestaurantActive($restaurant);

            //We can have a usecase when lading id disabled
            if(config('settings.disable_landing')){
                return redirect('/login')->withStatus(__('notifications_thanks_andcheckemail'));
            }else{
                //Normal, go to landing
                return redirect()->route('front')->withStatus(__('notifications_thanks_andcheckemail'));
            }

            
        } else {
            //Foodtiger
            return redirect()->route('newrestaurant.register')->withStatus(__('notifications_thanks_and_review'));
        }
    }

    private function makeRestaurantActive(Restorant $restaurant)
    {
        //Activate the restaurant
        $restaurant->active = 1;
        $restaurant->subdomain = $this->makeAlias($restaurant->name);
        $restaurant->update();

        $owner = $restaurant->user;

        //if the restaurant is first time activated
        if ($owner->password == null) {
            //Activate the owner
            $generatedPassword = Str::random(10);

            $owner->password = Hash::make($generatedPassword);
            $owner->active = 1;
            $owner->update();

            //Send email to the user/owner
            $owner->notify(new RestaurantCreated($generatedPassword, $restaurant, $owner));
        }
    }

    public function activateRestaurant(Restorant $restaurant)
    {
        $this->makeRestaurantActive($restaurant);

        return redirect()->route('admin.restaurants.index')->withStatus(__('Restaurant successfully activated.'));
    }

    public function restaurantslocations()
    {
        //TODO - Method for admin onlt
        if (! auth()->user()->hasRole('admin')) {
            dd('Not allowed');
        }

        $toRespond = [
            'restaurants'=> Restorant::where('active', 1)->get(),
        ];

        return response()->json($toRespond);
    }

    public function removedemo()
    {
        //Find by phone number (530) 625-9694
        $demoRestaurants = Restorant::where('phone', '(530) 625-9694')->get();
        foreach ($demoRestaurants as $key => $restorant) {
            $restorant->delete();
        }

        return redirect()->route('settings.index')->withStatus(__('Demo resturants removed.'));
    }

    public function callWaiter(Request $request)
    {
        $CAN_USE_PUSHER = strlen(config('broadcasting.connections.pusher.app_id')) > 2 && strlen(config('broadcasting.connections.pusher.key')) > 2 && strlen(config('broadcasting.connections.pusher.secret')) > 2;
        if ($request->table_id) {
            $table = Tables::where('id', $request->table_id)->get()->first();

            if (!$table->restaurant->getConfig('disable_callwaiter', 0) && $CAN_USE_PUSHER) {
                $msg = __('notifications_notification_callwaiter');

                event(new CallWaiter($table, $msg));

                return redirect()->back()->withStatus('The restaurant is notified. The waiter will come shortly!');
            }
        } else {
            return redirect()->back()->withStatus('Please select table');
        }
    }

    public function shareMenu()
    {
        $this->authChecker();

        if (config('settings.wildcard_domain_ready')) {
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://').auth()->user()->restorant->subdomain.'.'.str_replace('www.', '', $_SERVER['HTTP_HOST']);
        } else {
            $url = route('vendor', auth()->user()->restorant->subdomain);
        }

        return view('restorants.share', ['url' => $url, 'name'=>auth()->user()->restorant->name]);
    }

    public function downloadQR()
    {
        $this->authChecker();

        if (config('settings.wildcard_domain_ready')) {
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://').auth()->user()->restorant->subdomain.'.'.str_replace('www.', '', $_SERVER['HTTP_HOST']);
        } else {
            $url = route('vendor', auth()->user()->restorant->subdomain);
        }
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=512x512&data='.$url;
        $filename = 'qr.png';
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        @copy($url, $tempImage);

        return response()->download($tempImage, $filename);
    }

    /**
     * Store a new language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeNewLanguage(Request $request)
    {

        //Obtain the restaurant and all the data needed
        $data = Restorant::with('categories.items.extras')->with('categories.items.options')->where('id', $request->restaurant_id)->get()->toArray();
        $categoriesData = $data[0]['categories'];

        //1. Get the new locale and the current locale
        $newLocale = $request->locale;
        $currentLocale = config('app.locale');

        $newEnvLanguage = isset(config('config.env')[2]['fields'][0]['data'][$newLocale]) ? config('config.env')[2]['fields'][0]['data'][$newLocale] : 'UNKNOWN';

        //Create new language
        $localMenu = new LocalMenu([
            'restaurant_id'=>$request->restaurant_id,
             'language'=>$newLocale,
              'languageName'=>$newEnvLanguage,
              'default'=>'0', ]
        );
        $localMenu->save();

        //dd($newLocale);

        //2. Translate from the previous locale
        foreach ($categoriesData as $keyC => $category) {
            (Categories::class)::findOrFail($category['id'])->setTranslation('name', $newLocale, $category['name'])->save();
            foreach ($category['items'] as $keyI => $item) {
                (Items::class)::findOrFail($item['id'])->setTranslation('name', $newLocale, $item['name'])->save();
                (Items::class)::findOrFail($item['id'])->setTranslation('description', $newLocale, $item['description'])->save();
                foreach ($item['extras'] as $keyI => $extra) {
                    (Extras::class)::findOrFail($extra['id'])->setTranslation('name', $newLocale, $extra['name'])->save();
                }
                foreach ($item['options'] as $keyO => $option) {
                    (Options::class)::findOrFail($option['id'])->setTranslation('name', $newLocale, $option['name'])->save();
                }
            }
        }

        //3. Change locale to the new local
        app()->setLocale($newLocale);
        session(['applocale_change' => $newLocale]);

        //4. Clear cache
        // Artisan::call('config:clear');
        //Artisan::call('cache:clear');
        //Cache::flush();

        //5. Redirect
        return redirect()->route('items.index')->withStatus(__('New language successfully created.'));
    }
}
