<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        if(!config('settings.is_whatsapp_ordering_mode')){
            if(config('settings.is_pos_cloud_mode')){
                //Pos cloud
                DB::table('plan')->insert([
                    'name' => 'Free',
                    'limit_items'=>30,
                    'limit_orders'=>100,
                    'price'=>0,
                    'paddle_id'=>'',
                    'description'=>'For small restaurant or bars. You can upgrade ',
                    'features'=>'Full access to POS tool, Expenses and Profit, 100 orders per month , Dine in + TakeAway and Delivery Orders',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'enable_ordering'=>1,
                ]);
        
                DB::table('plan')->insert([
                    'name' => 'Pro',
                    'limit_items'=>0,
                    'limit_orders'=>0,
                    'price'=>49,
                    'paddle_id'=>'',
                    'description'=>'For bigger restaurants and bars. Limitless plan',
                    'features'=>'Full access to POS tool, Expenses and Profit, Unlimited orders included, Dine in + TakeAway and Delivery Orders, Dedicated support, Cancel anytime',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'enable_ordering'=>1,
                    'period'=>1,
                ]);
        
                DB::table('plan')->insert([
                    'name' => 'Pro Yearly',
                    'limit_items'=>0,
                    'limit_orders'=>0,
                    'price'=>499,
                    'paddle_id'=>'',
                    'description'=>'Get 2 months free when on yearly plan',
                    'features'=>'Full access to POS tool, Expenses and Profit, Unlimited orders included, Dine in + TakeAway and Delivery Orders',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'enable_ordering'=>1,
                    'period'=>2,
                ]);
            }else{
                //QR and FT
                if(config('app.isft')){
                    //FT
                    DB::table('plan')->insert([
                        'name' => 'Free',
                        'limit_items'=>30,
                        'limit_orders'=>100,
                        'price'=>0,
                        'paddle_id'=>'',
                        'description'=>'If you run a small restaurant or bar, or just need the basics, this plan is great.',
                        'features'=>'Accept 100 Orders/m, Access to the menu creation tool, Unlimited views, 30 items in the menu, Community support',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'enable_ordering'=>1,
                    ]);
            
                    DB::table('plan')->insert([
                        'name' => 'Starter',
                        'limit_items'=>0,
                        'limit_orders'=>600,
                        'price'=>99,
                        'paddle_id'=>'',
                        'description'=>'For bigger restaurants and bars. Offer a full menu.',
                        'features'=>'Accept 600 Orders/m, Access to the menu creation tool, Unlimited views, Unlimited items in the menu, Dedicated Support',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'enable_ordering'=>1,
                    ]);
            
                    DB::table('plan')->insert([
                        'name' => 'Pro',
                        'limit_items'=>0,
                        'limit_orders'=>0,
                        'price'=>120,
                        'paddle_id'=>'',
                        'period'=>1,
                        'description'=>'Accept orders directly from your customer mobile phone',
                        'features'=>'Featured on our site, Accept unlimited Orders, Manage order, Full access to QR tool, Access to the menu creation tool, Unlimited views, Unlimited items in the menu, Dedicated Support',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'enable_ordering'=>1,
                    ]);
                }else{
                    //QR
                    DB::table('plan')->insert([
                        'name' => 'Free',
                        'limit_items'=>30,
                        'limit_orders'=>0,
                        'price'=>0,
                        'paddle_id'=>'',
                        'description'=>'If you run a small restaurant or bar, or just need the basics, this plan is great.',
                        'features'=>'Full access to QR tool, Access to the menu creation tool, Unlimited views, 30 items in the menu, Community support',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'enable_ordering'=>2,
                    ]);
            
                    DB::table('plan')->insert([
                        'name' => 'Starter',
                        'limit_items'=>0,
                        'limit_orders'=>300,
                        'price'=>9,
                        'paddle_id'=>'',
                        'description'=>'For bigger restaurants and bars. Offer a full menu. Limitless plan',
                        'features'=>'Full access to QR tool, Access to the menu creation tool, Unlimited views, Unlimited items in the menu, 300 orders per month, Dedicated Support',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'enable_ordering'=>1,
                    ]);
            
                    DB::table('plan')->insert([
                        'name' => 'Pro',
                        'limit_items'=>0,
                        'limit_orders'=>0,
                        'price'=>49,
                        'paddle_id'=>'',
                        'period'=>1,
                        'description'=>'Accept orders directly from your customer mobile phone',
                        'features'=>'Accept Orders, Manage order, Full access to QR tool, Access to the menu creation tool, Unlimited views, Unlimited items in the menu, Unlimited orders, Dedicated Support',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'enable_ordering'=>1,
                    ]);
                }
                
            }
           
        }else{
           
            //Whatsapp
            DB::table('plan')->insert([
                'name' => 'Lite',
                'limit_items'=>0,
                'limit_orders'=>0,
                'price'=>0,
                'paddle_id'=>'',
                'description'=>'Use it for free and upgrade as you grow',
                'features'=>'Full access to Menu Creation tool, Unlimited views, 30 items in the menu, Community support',
                'created_at' => now(),
                'updated_at' => now(),
                'enable_ordering'=>2,
            ]);
    
            DB::table('plan')->insert([
                'name' => 'Pro',
                'limit_items'=>0,
                'limit_orders'=>0,
                'price'=>19,
                'paddle_id'=>'',
                'period'=>1,
                'description'=>'For growing business that needs more',
                'features'=>'Full access to Menu Creation tool, Unlimited views, Unlimited orders, Email support',
                'created_at' => now(),
                'updated_at' => now(),
                'enable_ordering'=>1,
            ]);
        }
    }
}
