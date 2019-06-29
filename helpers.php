<?php

use Modules\User\Entities\Sentinel\User;
use Modules\Ishoppingcart\Entities\Status;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request as Requests;
use Illuminate\Routing\Router;

use Modules\Ishoppingcart\Entities\Coupon as Coupon;
use Modules\Ishoppingcart\Entities\Order as Order;
use Modules\Ishoppingcart\Entities\OrderItems as OrderItems;
use Modules\Ishoppingcart\Entities\OrderCoupon as OrderCoupon;
use Modules\Ishoppingcart\Entities\Transaction as Transaction;
use Modules\Ibooking\Entities\Reservation as Reservation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Foundation\Application;


if (! function_exists('executtePostOrder')){

    function executtePostOrder($orderID,$status,$request){

        /*
        const DECLINED = 0;
        const APPROVED = 1;
        const PENDING = 2;
        const EXPIRED = 3;
        const ERROR = 4;
        */

        Order::where("id",$orderID)->update(['status' => $status]);
        Transaction::where("order_id",$orderID)->update(['status' => $status]);
        $orderItems = OrderItems::where("order_id",$orderID)->first();
        $reservationID = $orderItems->product_id;

        $reservation = Reservation::find($reservationID);


        if($status==0 || $status==4 ){

            if($request->session()->exists('couponID')) {
                    $couponID = session('couponID');
                    $deleteOrderCoupon = OrderCoupon::where('order_id',$orderID)
                    ->where('coupon_id',$couponID)
                    ->delete();
                    $coupon = Coupon::find($couponID);

                    // Actualizo cant del cupon
                    $coupon->cant = $coupon->cant + 1;
                    $coupon->save();
            }
        }

            $reservationDate = $reservation->start_date;
            $updateReservation = Reservation::where("id",$reservationID)
                ->update(['status' => $status, 'start_date' =>$reservationDate ]);




        return $data=['orderitem'=>$orderItems,'reservation'=>$reservation];

    }


}


if(!function_exists('emailSend')){

    function emailSend($options=array()){
        $default_options = array(
            'email_from' => array(),
            'theme' => null,
            'email_to' => array(),
            'subject' => null,
            'sender'=>null,
            'data' => array(
                'title' => null,
                'intro'=>null,
                'content'=>array(),
                ),
        );

        $options = array_merge($default_options, $options);
        $response = array();
      try {
            $data = $options['data'];

            /**
             * Send email
             */

            $email_to  = $options['email_to'];
            $email_from = $options['email_from'];

            $sender  = $options['sender'];
            $subject     = $options['subject'];

            Mail::send($options['theme'],
                [
                    'data' => $data,
                ], function($message) use ($email_to,$sender,$subject,$email_from) {
                    $message->to($email_to,$sender)
                        ->from($email_from, $sender)
                        ->subject($subject);
                });
          $response['status'] = 'success';
          $response['msg']= '';

        } catch( \Throwable $t) {

            $response['status'] = 'error';
            $response['msg']= $t->getMessage();
        }

return $response;




    }
}


if (! function_exists('getTypePayment')){

    function getTypePayment($orderID){

        $orderItems = OrderItems::where("order_id",$orderID)->first();

        if($orderItems->product_type == "\Modules\Ishoppingcart\Entities\Coupon"){
            return "giftcard";
        }else{
            return "reservation";
        }

    }

}

if (! function_exists('executtePostOrderGiftcard')){

    function executtePostOrderGiftcard($orderID,$status,$request){

        Order::where("id",$orderID)->update(['status' => $status]);
        Transaction::where("order_id",$orderID)->update(['status' => $status]);
        $orderItems = OrderItems::where("order_id",$orderID)->first();
       
        $coupon = Coupon::find($orderItems->product_id);

        return $data=['orderitem'=>$orderItems,'coupon'=>$coupon];

    }


}