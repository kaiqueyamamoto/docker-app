<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Restorant;      
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Authenticates user, send api token
     */
    public function getToken(Request $request)
    {
        $user = User::where(['active'=>1, 'email'=>$request->email])->first();
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->hasRole(['owner'])) {
                    return response()->json([
                        'status' => true,
                        'token' => $user->api_token,
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'errMsg' => __('User is not a vendor!'),
                    ]);
                }
            } else {
                return $this->invalidResponse();
            }
        } else {
            return $this->invalidResponse();
        }
    }

    public function register(Request $request)
    {
        
            $validator = Validator::make($request->all(), [
                'vendor_name' => ['required', 'string', 'max:255','unique:companies,name'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'unique:users', 'max:255'],
                'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            if (!$validator->fails()) {
                $vendor = new User;

                $vendor->name = $request->name;
                $vendor->email = $request->email;
                $vendor->phone = $request->phone;
                $vendor->password = Hash::make($request->password);
                $vendor->api_token = Str::random(80);
                $vendor->save();

                //Assign role
                $vendor->assignRole('owner');

                //Create Restorant
                $restaurant = new Restorant;
                $restaurant->name = strip_tags($request->vendor_name);
                $restaurant->user_id = $vendor->id;
                $restaurant->description = strip_tags('');
                $restaurant->minimum =  0;
                $restaurant->lat = 0;
                $restaurant->lng = 0;
                $restaurant->active = config('app.isqrsaas')?1:0; //yes in qr and wp, no in ft
                $restaurant->address = '';
                $restaurant->phone = $vendor->phone;
                $restaurant->subdomain = $this->makeAlias(strip_tags($request->vendor_name));
                //$restaurant->logo = "";
                $restaurant->save();

                //Send email to the user/owner
               if(config('app.isqrsaas')){
                   //qr wp
                return response()->json([
                    'status' => true,
                    'token' => $vendor->api_token,
                    'id' => $vendor->id,
                ]);
               }else{
                   //FT
                return response()->json([
                    'status' => false,
                    'errMsg'=>__("Restaurant account created. Please wait for a call from us to activate your account.")
                ]);
               }


                
            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => $validator->errors(),
                ]);
            }
        
    }

    /**
     * Return invalid user data
     */
    private function invalidResponse($message='User not found!'){
        return response()->json([
            'status' => false,
            'errMsg' => __($message),
        ]);
    }

    /**
     * Get driver data
     */
    public function getUseData()
    {
        $user = User::where(['api_token' => $_GET['api_token']])->first();

        if ($user) {
            return response()->json([
                'status' => true,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ? $user->phone : ''
                ]
            ]);
        } else {
            return $this->invalidResponse();
        }
    }
}
