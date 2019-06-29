<?php

namespace Modules\Ishoppingcart\Http\Controllers;

use Mockery\CountValidator\Exception;
use Modules\Ishoppingcart\Repositories\CouponRepository;
use Modules\Ishoppingcart\Repositories\PaymentRepository;
use Modules\Ishoppingcart\Repositories\OrderRepository;
use Modules\Ishoppingcart\Repositories\TransactionRepository;
use Modules\Ishoppingcart\Repositories\OrderItemsRepository;
use Modules\Ishoppingcart\Repositories\OrderCouponRepository;

use Modules\Ishoppingcart\Entities\Order as Order;
use Modules\Ishoppingcart\Entities\Status as Status;
use Modules\Ishoppingcart\Entities\OrderItems as OrderItems;
use Modules\Ishoppingcart\Entities\Transaction as Transaction;
use Modules\Ishoppingcart\Entities\Transaction_status as TransactionStatus;
use Modules\Ishoppingcart\Entities\OrderCoupon as OrderCoupon;

use Modules\Ibooking\Entities\Reservation as Reservation;

use Modules\Setting\Contracts\Setting;

use Modules\Core\Http\Controllers\BasePublicController;
use Route;
use Request;
use Log;
use Session;
use DateTime;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request as Requests;
use Illuminate\Routing\Router;

use Modules\Ishoppingcart\Entities\Coupon;

class PublicController extends BasePublicController
{

    private $coupon;
    private $payment;
    private $order;
    private $transaction;
    private $orderItems;
    private $orderCoupon;
    private $setting;


    public function __construct(CouponRepository $coupon, PaymentRepository $payment, OrderRepository $order,TransactionRepository $transaction,OrderItemsRepository $orderItems, OrderCouponRepository $orderCoupon,Setting $setting)
    {

        parent::__construct();
        $this->coupon = $coupon;
        $this->payment = $payment;
        $this->order = $order;
        $this->transaction = $transaction;
        $this->orderItems = $orderItems;
        $this->orderCoupon = $orderCoupon;
        $this->setting = $setting;
    }

    public function index(){

        $tpl ='ishoppingcart::frontend.index';
        $ttpl='ishoppingcart.index';

        if(view()->exists($ttpl)) $tpl = $ttpl;

        //$payments = $this->payment->all();
        $payments = $this->payment->searchByStatus(1);

        return view($tpl, compact('payments'));

    }



    public function findCoupon(){

        if(Request::ajax()){


            $couponCode1 = trim(Input::get("couponCode"));
            $valueTotal = Input::get("valueTotal");

            $couponCode = strip_tags($couponCode1);

            $coupon_type = "";
            $coupon_value = "";

            $dateNow = date("Y-m-d");

            $coupon = $this->coupon->findByCode($couponCode,$dateNow);

            if(count($coupon)==0){
                $responseP = 0;
            }else{

                if(($coupon->cant>0)){

                    $responseP = 2;
                    $coupon_type = $coupon->type;
                    $coupon_value = $coupon->value;
                }else{
                    $responseP = 1;
                }

            }

            return response()->json([
                'response' => $responseP,
                'couponType' => $coupon_type,
                'couponValue' => $coupon_value
            ]);
        }

    }

    public function checkoutProcess(Requests $request){


        $productID = $request->reservationID;
        $paymentID = $request->paymentID;

        //====== Obtener toda informacion de la reserva.
        $reservation = Reservation::find($productID);

        $amount = $reservation->value;
        $couponID = $reservation->coupon_id;

        // Verifico si la orden de ese product ya existe

       // $orderItems = $this->orderItems->findByProductId($productID);

       // if(count($orderItems)==0){
            $discountOp = false;
            $coupon_value = 0;

            //====== Descontar el Amount si tiene Cupon
            if(!empty($couponID)){

                $dateNow = date("Y-m-d");
                $coupon = $this->coupon->findById($couponID,$dateNow);

                if(count($coupon)>0){
                    if($coupon->cant>0){

                        $coupon_type = $coupon->type;
                        $coupon_value = $coupon->value;

                        // Actualizo cant del cupon
                        $coupon->cant = $coupon->cant - 1;
                        $coupon->save();

                        // Obtengo el valor que debe cancelar (Porcentaje) o (Fijo)
                        if($coupon_type=="p"){
                            $discount = ($amount * $coupon_value) / 100;
                        }else{
                            $discount = $coupon_value;
                        }
                        $discountOp = true;
                        $request->session()->put('couponID', $couponID);
                        $amount = $amount - $discount;
                    }
                }
            }

            //====== Proceso para generar orden

            $productType = "\Modules\Ibooking\Entities\Reservation";

            $orderID = $this->executteOrderProcess($userID=1,$paymentID,$productID,$amount,$discountOp,$couponID,$coupon_value,$productType);

    //    }

        //====== Proceso buscar ruta para Pago

        $payment = $this->payment->findById($paymentID)->config;

        $collection = collect($payment);
        $config = $collection->pluck('desc','label');

        $paymentRoute = $config['route'];

        if(isset($paymentRoute)){

            // Session
            $request->session()->put('orderID', $orderID);
            return redirect()->route($paymentRoute);

        }else{

            //$locale = LaravelLocalization::setLocale() ?: App::getLocale();
            //return redirect()->route($locale.'.checkout');
        }

    }

    public function executteOrderProcess($userID,$paymentID,$productID,$amount,$discountOp,$couponID,$couponValue,$productType){

        //================================================================ Order

        $orderID = $this->createOrder($amount, $userID);

        //================================================================ OrderItems

        $tax = $this->setting->get('ishoppingcart::orderitems-tax');

        $this->createOrderItems($orderID, $productID, $productType,$amount,$tax);

        //================================================================ Order Coupon


        if($discountOp==true){
            $value = $couponValue;
            $this->createOrderCoupon($couponID,$orderID,$value);
        }

        //================================================================   Transaction

        $this->createTransaction($orderID,$paymentID,$amount);

        return $orderID;

    }

    public function createOrder($amount,$userID){

        $newOrder = new Order;
        $newOrder->amount = $amount;
        $newOrder->user_id = $userID;
        $newOrder->status = 2;
        $newOrder->save();

        return $newOrder->id;
    }

    public function createOrderItems($orderID,$productID,$productType,$price,$tax){

        $newOrderItem = new OrderItems;
        $newOrderItem->order_id = $orderID;
        $newOrderItem->product_id = $productID;
        $newOrderItem->product_type = $productType;
        $newOrderItem->price = $price;
        $newOrderItem->tax = $tax;

        $newOrderItem->save();
    }

    public function createOrderCoupon($couponID, $orderId, $value){

        $newOrderCoupon = new OrderCoupon;
        $newOrderCoupon->coupon_id = $couponID;
        $newOrderCoupon->order_id = $orderId;
        $newOrderCoupon->value = $value;

        $newOrderCoupon->save();

    }

    public function createTransaction($orderID,$paymentID,$amount){

        $newTransaction = new Transaction;
        $newTransaction->order_id = $orderID;
        $newTransaction->payment_id = $paymentID;
        $newTransaction->amount = $amount;
        $newTransaction->status = 2;

        $newTransaction->save();

    }

    // Request : email, eventid, price
    public function checkoutGiftcard(Requests $request){

        if(empty($request->email)){
            return redirect()->back();
        }

        $tpl ='ishoppingcart::frontend.giftcard.index';
        $ttpl='ishoppingcart.giftcard.index';

        if(view()->exists($ttpl)) $tpl = $ttpl;

        //$payments = $this->payment->all();
        $payments = $this->payment->searchByStatus(1);
        

        return view($tpl, compact('payments','request'));

    }

    public function checkoutProcessGiftcard(Requests $request){

        
        $email = $request->email;
        $eventID = $request->eventid;
        $price = $request->price;

        //=================== Revisar que el precio del evento es el correcto
        /*
        $entitie = "\Modules\Ibooking\Entities\Event";
        $check = $this->checkEvent($eventID,$entitie,$price);

        if($check==false)
            return redirect()->route("homepage");
        */

        /**
         * El modulo cuando se realizo fue para Multiples Eventos con 1 solo precio (2017)
         * Por pedido del Cliente se cambia el valor del Cupon a 70, no por el precio del evento
         * Ya que se integran multiples precios para 1 evento.
         */

        //$price = 70; // Cambio solicitado por el cliente

        $paymentID = $request->paymentID;

        //=================================== Genero Cupon
        $code = $this->addCouponCode();

        $newCoupon = new Coupon;
        $newCoupon->name = "Tarjeta de Regalo";
        $newCoupon->code = $code;
        $newCoupon->type = "f";
        $newCoupon->cant = 1;

        $porcen = 100; // Porcentaj en base al Precio del Evento
        $amount = ($price * $porcen) / 100;
        $newCoupon->value = $amount;

        $newCoupon->from = date("Y-m-d");
        $newCoupon->to = "2030-10-10";

        $newCoupon->email = $email;
        
        $newCoupon->save();
        $productID = $newCoupon->id;

        $discountOp = false;
        $coupon_value = 0;
        $couponID = $newCoupon->id;
        $productType = "\Modules\Ishoppingcart\Entities\Coupon";

        
        //====== Proceso para generar orden
        $orderID = $this->executteOrderProcess($userID=1,$paymentID,$productID,$amount,$discountOp,$couponID,$coupon_value,$productType);

        //====== Proceso buscar ruta para Pago

        $payment = $this->payment->findById($paymentID)->config;
        $collection = collect($payment);
        $config = $collection->pluck('desc','label');
        $paymentRoute = $config['route'];

        
        if(isset($paymentRoute)){

            // Session
            $request->session()->put('orderID', $orderID);
            return redirect()->route($paymentRoute);

        }else{

            //$locale = LaravelLocalization::setLocale() ?: App::getLocale();
            //return redirect()->route($locale.'.checkout');
        }

    }

    public function checkEvent($eventID,$entitie,$price){

        $event = $entitie::query()->where("id",$eventID)->first();

        if(isset($event->attribute)){

            foreach ($event->attribute as $index => $attr):
                if((strtolower($attr->type))=="precio"):
                    $priceBD = $attr->value; 
                endif; 
            endforeach;

            if($priceBD==$price):
                return true;
            endif;

        }

        return false;

    }

    public function addCouponCode(){

        $code = "";

        $couponFormat = $this->setting->get('ishoppingcart::coupon-format');
        $codePrefix = $this->setting->get('ishoppingcart::code-prefix');
        $codeSufix = $this->setting->get('ishoppingcart::code-sufix');
        
        $uppercase = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];

        $lowercase = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];

        $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

        $characters = [];

        if(empty($couponFormat))
            $couponFormat = "alphabetic";

        if($couponFormat=="alphabetic"){
            $characters = array_merge($characters, $lowercase, $uppercase);  
        }

        if($couponFormat=="numeric"){
            $characters = array_merge($characters, $numbers);  
        }

        if($couponFormat=="alphanumeric"){
            $characters = array_merge($characters, $numbers, $lowercase, $uppercase);  
        }

        // Asumiendo que se pudiera repetir el codigo que se genera
        //=============================================
        $result = 1;

        while($result==1){
            $code = $this->generaterCouponCode($characters);
            $result = Coupon::where("code",$code)->count();
        }
        //=============================================

       
        if(!empty($codePrefix))
            $codePrefix = $codePrefix ."-";

        if(!empty($codeSufix))
            $codeSufix = "-".$codeSufix;

        return $codePrefix.$code.$codeSufix;

    }

    public function generaterCouponCode($characters){

        $cont = 0;
        $contdash = 0;
        $code = "";

        $codeLENGHT = $this->setting->get('ishoppingcart::code-lenght');
        $dashEvery = $this->setting->get('ishoppingcart::dash-every');

        while($cont < $codeLENGHT){

            $code .= $characters[mt_rand(0, count($characters) - 1)];

            $contdash++;
            $cont++;

            if($contdash==$dashEvery && $cont<$codeLENGHT){
                $code .= "-";
                $contdash = 0;
            }

        }

        return $code;

    }

}