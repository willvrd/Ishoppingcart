<?php

namespace Modules\Ishoppingcart\Http\Controllers;

use Illuminate\Http\Request as Requests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ishoppingcart\Entities\Payment;
use Modules\Ishoppingcart\Entities\Paypal;
use Modules\Ishoppingcart\Entities\Order;
use Illuminate\Support\Facades\Mail;
use Modules\Setting\Contracts\Setting;
use Route;
use Request;

use Illuminate\Support\Facades\Log;

class PaypalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $_apiContext;
    private $paiament;
    private $order;
    private $paypal;
    private $setting;


    public function __construct(Payment $payment, Order $order, Setting $setting)
    {
        $this->order = $order;
        $this->paiament = $payment;
        $this->setting=$setting;


    }

    public function index(Requests $request)
    {
        if ($request->session()->exists('orderID')) {
            $orderID = session('orderID');
            $order = $this->order->find($orderID);
            $idproduct = $order->orderitems->first()->product_id;
            $entitie = $order->orderitems->first()->product_type;
            $product = $entitie::query()->find($idproduct);
            //orider descriptions
            $amount = $order->amount;

        }
        $paypal_config = $this->paiament->where('name', 'Paypal')->first()->config;
        
        $collection = collect($paypal_config);

        $config = $collection->pluck('desc', 'label');
        $this->paypal = new Paypal($config);
        //dd($config);
        $payment = $this->paypal->generate($product, $amount, $orderID);
        return redirect($payment->getApprovalLink());
    }


    public function store(Requests $request)
    {
        $email_from = $this->setting->get('iforms::from-email');
        $email_to=explode(',',$this->setting->get('iforms::form-emails'));
        $sender  = $this->setting->get('core::site-name');

        try {
            $paypal_config = $this->paiament->where('name', 'Paypal')->first()->config;
            $collection = collect($paypal_config);
            $config = $collection->pluck('desc', 'label');
            $this->paypal = new Paypal($config);
            $response = $this->paypal->execute($request->paymentId, $request->PayerID);

            if ($response->state == "approved") {

                $typePayment = getTypePayment($response->transactions[0]->invoice_number);

                if($typePayment=="reservation"){

                    $success_process = executtePostOrder($response->transactions[0]->invoice_number,1,$request);
                    $reservation=$success_process['reservation'];
                    $content=['order'=>$response->transactions[0]->invoice_number,'reservation'=>$reservation];
                    $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $reservation->email,'subject' => 'Confirmación de pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de pago de orden','intro'=>'Felicidades su reservación fue exitosa','content'=>$content,)]);
                    $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $email_to,'subject' => 'Confirmación de pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de pago de orden','intro'=>'Confirmación de Nueva Orden','content'=>$content,)]);

                }else{

                    $success_process = executtePostOrderGiftcard($response->transactions[0]->invoice_number,1,$request);

                    $coupon=$success_process['coupon'];
                    $content=['order'=>$response->transactions[0]->invoice_number,'coupon'=>$coupon];

                    $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $coupon->email,'subject' => 'Confirmación de pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de pago de orden','intro'=>'Felicidades su orden de cupon fue exitosa','content'=>$content,)]);
                    $confimail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $email_to,'subject' => 'Confirmación de Nueva Orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de Nueva Orden','intro'=>'Nueva solicitud de cupon Realizada','content'=>$content,)]);

                }

            }else{

                $typePayment = getTypePayment($response->transactions[0]->invoice_number);

                if($typePayment=="reservation"){

                    $success_process = executtePostOrder($response->transactions[0]->invoice_number,4,$request);
                    $reservation=$success_process['reservation'];
                    $content=['order'=>$response->transactions[0]->invoice_number];
                    emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => $reservation->email,'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

                }else{

                    $success_process = executtePostOrderGiftcard($response->transactions[0]->invoice_number,4,$request);

                    $coupon=$success_process['coupon'];
                    $content=['order'=>$response->transactions[0]->invoice_number];

                    emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$coupon->email],'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

                }

            }

        } catch (Exception $e) {

            $typePayment = getTypePayment($response->transactions[0]->invoice_number);

            if($typePayment=="reservation"){

                $success_process = executtePostOrder($response->transactions[0]->invoice_number,4,$request);
                $reservation=$success_process['reservation'];
                $content=['order'=>$response->transactions[0]->invoice_number];

                emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' =>$reservation->email,'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);
            
            }else{

               $success_process = executtePostOrderGiftcard($response->transactions[0]->invoice_number,4,$request);

                $coupon=$success_process['coupon'];
                $content=['order'=>$response->transactions[0]->invoice_number];

                emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$coupon->email],'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

            }

        }
    
        return  redirect()->route('homepage');
    }


    public function ko(Requests $request)
    {
        $email_from = $this->setting->get('iforms::from-email');
        $email_to=explode(',',$this->setting->get('iforms::form-emails'));
        $sender  = $this->setting->get('core::site-name');
        if ($request->session()->exists('orderID')) {
            $orderID = session('orderID');

            $typePayment = getTypePayment($orderID);

            if($typePayment=="reservation"){

                $success_process = executtePostOrder($orderID,4,$request);
                $reservation=$success_process['reservation'];
                $content=['order'=>$orderID];
                emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => $reservation->email,'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);
            }else{

                $success_process = executtePostOrderGiftcard($orderID,4,$request);
                $coupon=$success_process['coupon'];
                $content=['order'=>$orderID];
                emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => $coupon->email,'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

            }

            return  redirect()->route('homepage');
        }
        return  redirect()->route('homepage');
    }
}
