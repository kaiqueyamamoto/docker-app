<?php

namespace App\Http\Controllers;

use App\Order;

use Cart;
use Illuminate\Http\Request;




class PaymentController extends Controller
{
    public function view(Request $request)
    {
        $total = Money(Cart::getSubTotal(), config('settings.cashier_currency'), config('settings.do_convertion'))->format();

        //Clear cart
        Cart::clear();

        return view('payment.payment', [
            'total' => $total,
        ]);
    }

    public function handleOrderPaymentStripe(Request $request,Order $order){
        if($request->success.""=="true"){
            $order->payment_status = 'paid';
            $order->update();
            return redirect()->route('order.success', ['order' => $order]);
        }else{
            //TODO - handle better when executed on mobile app
            return redirect()->route('vendor',$order->restorant->subdomain)->withMesswithErrorage($request->message)->withInput();
        }
    }

    
}
