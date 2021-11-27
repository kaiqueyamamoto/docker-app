<?php

namespace App\Http\Controllers;

use App\Plans;
use App\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\PaymentActionRequired;

class PlansController extends Controller
{
   

    public function current()
    {

        //The curent plan -- access for owner only
        if (! auth()->user()->hasRole('owner')) {
            abort(403, 'Unauthorized action.');
        }

        $theSelectedProcessor=strtolower(config('settings.subscription_processor','stripe'));


        if(!($theSelectedProcessor == 'stripe' || $theSelectedProcessor == 'local')&&auth()->user()->plan_status!="set_by_admin" ){
            $className = '\Modules\\'.ucfirst($theSelectedProcessor).'Subscribe\Http\Controllers\App';
            $ref = new \ReflectionClass($className);
            $ref->newInstanceArgs()->validate(auth()->user());
        }


        $plans = Plans::get()->toArray();
        $colCounter = [4, 12, 6, 4, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4];

        $currentUserPlan = Plans::withTrashed()->find(auth()->user()->mplanid());
        $planAttribute=auth()->user()->restorant->getPlanAttribute();

        $data = [
            'col'=>$colCounter[count($plans)],
            'plans'=>$plans,
            'currentPlan'=>$currentUserPlan,
            'planAttribute'=>$planAttribute
        ];

        
        if ($theSelectedProcessor == 'stripe') {
            $data['intent'] = auth()->user()->createSetupIntent();

            if (auth()->user()->subscribed('main')) {
                //Subscribed
                //Switch the user to the free plan
                //auth()->user()->plan_id=config('settings.free_pricing_id');
                //auth()->user()->update();
                //$currentUserPlan=Plans::findOrFail(auth()->user()->mplanid());
                //$data['currentPlan']=$currentUserPlan;
            } else {
                //not subscribed
            }
        }

        $data['subscription_processor']=$theSelectedProcessor;

        return view('plans.current', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Plans $plans)
    {
        $this->adminOnly();

        return view('plans.index', ['plans' => $plans->paginate(10)]);
    }

   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->adminOnly();
        $theSelectedProcessor=strtolower(config('settings.subscription_processor','stripe'));

        return view('plans.create',['theSelectedProcessor'=>$theSelectedProcessor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->adminOnly();
        $plan = new Plans;
        $plan->name = strip_tags($request->name);
        $plan->price = strip_tags($request->price);
        $plan->limit_items = strip_tags($request->limit_items);
        

        if(isset($request->subscribe)){
            foreach ($request->subscribe as $key => $value) {
                $plan->$key = strip_tags($value);
            }
        }

        $plan->description = $request->description;
        $plan->features = $request->features;

        $plan->period = $request->period == 'monthly' ? 1 : 2;
        $plan->enable_ordering = $request->ordering == 'enabled' ? 1 : 2;

        $plan->limit_orders = $request->ordering == 'enabled'?$request->limit_orders:0;

        $plan->save();

        return redirect()->route('plans.index')->withStatus(__('Plan successfully created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Plans $plan)
    {
        $this->adminOnly();
        $theSelectedProcessor=strtolower(config('settings.subscription_processor','Stripe'));
        return view('plans.edit', ['plan' => $plan,"theSelectedProcessor"=>$theSelectedProcessor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plans $plan)
    {
        $this->adminOnly();
        $plan->name = strip_tags($request->name);
        $plan->price = strip_tags($request->price);
        $plan->limit_items = strip_tags($request->limit_items);

        //Subscriptions plans
        if(isset($request->subscribe)){
            foreach ($request->subscribe as $key => $value) {
                $plan->$key = strip_tags($value);
            }
        }

        //Default stripe
        if(isset($request->stripe_id)){
            $plan->stripe_id=$request->stripe_id;
        }

       

        $plan->period = $request->period == 'monthly' ? 1 : 2;
        $plan->enable_ordering = $request->ordering == 'enabled' ? 1 : 2;
        $plan->limit_orders = $request->ordering == 'enabled'?$request->limit_orders:0;

        $plan->description = $request->description;
        $plan->features = $request->features;

        $plan->update();

        return redirect()->route('plans.index')->withStatus(__('Plan successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plans $plan)
    {
        $this->adminOnly();
        $plan->delete();

        return redirect()->route('plans.index')->withStatus(__('Plan successfully deleted!'));
    }

    public function subscribe3dStripe(Request $request,Plans $plan,User $user){
        if($request->success.""=="true"){
            //Assign user to plan
            $user->plan_id = $plan->id;
            $user->update();

            return redirect()->route('plans.current')->withStatus(__('Plan update!'));
        }else{
            //TODO - handle better when executed on mobile app
            return redirect()->route('plans.current')->withEror($request->message)->withInput();
        }
    }

    public function subscribe(Request $request)
    {
        $plan = Plans::findOrFail($request->plan_id);

        if (config('settings.subscription_processor') == 'Stripe') {
            $plan_stripe_id = $plan->stripe_id;
            //Shold we do a swap
            try {
                if (auth()->user()->subscribed('main')) {
                    
                    //SWAP
                    auth()->user()->subscription('main')->swap($plan_stripe_id);
                } else {
                    //NEW Stripe 
                    //Surround with try
                    $payment_stripe = auth()->user()->newSubscription('main', $plan_stripe_id)->create($request->stripePaymentId);
                    
                }
            }
            catch (PaymentActionRequired $e) {
                //On PaymentActionRequired - send the checkout link
                $paymentRedirect = route('cashier.payment',[$e->payment->id, 'redirect' => route('plans.subscribe_3d_stripe',['plan'=> $plan->id,'user'=>auth()->user()->id])]);
                return redirect($paymentRedirect);
            }
        } 

        //Assign user to plan
        auth()->user()->plan_id = $plan->id;
        auth()->user()->update();

        return redirect()->route('plans.current')->withStatus(__('Plan update!'));
    }

    public function adminupdate(Request $request)
    {
        $this->adminOnly();
        $user = User::findOrFail($request->user_id);
        $user->plan_id = $request->plan_id;
        $user->plan_status = "set_by_admin";
        $user->update();

        return redirect()->route('admin.restaurants.edit', ['restaurant' => $request->restaurant_id])->withStatus(__('Plan successfully updated.'));
    }
}
