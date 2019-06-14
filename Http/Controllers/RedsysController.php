<?php

namespace Modules\Ishoppingcart\Http\Controllers;


use Illuminate\Http\Request as Requests;
use App\Http\Controllers\Controller;
use Modules\Ishoppingcart\Entities\Payment;
use Ssheduardo\Redsys\Facades\Redsys;
use Illuminate\Database\Eloquent\Collection;
use Modules\Ishoppingcart\Entities\Order;
use Illuminate\Support\Facades\Mail;
use Modules\Setting\Contracts\Setting;
use Route;
use Request;

use Illuminate\Support\Facades\Log;

class RedsysController extends Controller
{
    private $paiament;
    private $order;
    private $setting;


    public function __construct(Payment $payment, Order $order, Setting $setting)
    {
        $this->order= $order;
        $this->paiament = $payment;
        $this->setting = $setting;


    }

    public function index(Requests $request)
    {

        if($request->session()->exists('orderID')){
            $orderID = session('orderID');
            $order = $this->order->find($orderID);
            $idproduct=$order->orderitems->first()->product_id;
            $entitie=$order->orderitems->first()->product_type;
            $product=$entitie::query()->find($idproduct);
            $amount = $order->amount;
        }else{
            return redirect()->route('homepage');
        }

        $redsys_config = $this->paiament->where('name', 'Redsys')->first()->config;
        $collection = collect($redsys_config);
        $config = $collection->pluck('desc','label');

        if(isset($product->fullname))
            $productName = $product->fullname;
        else
            $productName = "Tarjeta de Regalo -".$product->email;

        if(isset($product->description))
            $productDesc = $product->description;
        else
            $productDesc = "Tarjeta de Regalo";

        try {


            $key = $config['key'];
            Redsys::setAmount($amount);
            Redsys::setOrder('000'.$orderID);
            Redsys::setMerchantcode($config['merchantcode']); //Reemplazar por el código que proporciona el banco
            Redsys::setCurrency('978');
            Redsys::setTransactiontype($config['transactiontype']);
            Redsys::setTerminal($config['terminal']);
            Redsys::setMethod('T');
            Redsys::setNotification(Route($config['url_notification'])); //Url de notificacion
            Redsys::setUrlOk(Route($config['url_ok'])); //Url OK
            Redsys::setUrlKo(Route($config['url_ko'])); //Url KO
            Redsys::setVersion('HMAC_SHA256_V1');
            Redsys::setTradeName($productName);
            Redsys::setTitular($productName);
            Redsys::setProductDescription($productDesc);
            Redsys::setEnviroment($config['enviroment']); //Entorno test
            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);
            $form = Redsys::executeRedirection();

            //dd($form);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $form;
    }


    public function notification(Requests $request)
    {

    }

    public function ok(Requests $request)
    {
        $redsys_config = $this->paiament->where('name', 'Redsys')->first()->config;
        $collection = collect($redsys_config);
        $config = $collection->pluck('desc','label');
        $Ds_MerchantParameters = $request->input('Ds_MerchantParameters');
        $email_from = $this->setting->get('iforms::from-email');
        $email_to=explode(',',$this->setting->get('iforms::form-emails'));
        $sender  = $this->setting->get('core::site-name');
        try {

            $key = $config['key'];

            $parameters = Redsys::getMerchantParameters($Ds_MerchantParameters);
            $DsResponse = $parameters["Ds_Response"];
            $DsResponse += 0;
            if (Redsys::check($key, $request) && $DsResponse <= 99) {

                $typePayment = getTypePayment(intval($parameters['Ds_Order']));
                
                if($typePayment=="reservation"){

                    $success_process = executtePostOrder(intval($parameters['Ds_Order']),1,$request);

                    $reservation=$success_process['reservation'];
                    $content=['order'=>intval($parameters['Ds_Order']),'reservation'=>$reservation];

                    $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $reservation->email,'subject' => 'Confirmación de pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de pago de orden','intro'=>'Felicidades su reservación fue exitosa','content'=>$content,)]);
                    $confimail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $email_to,'subject' => 'Confirmación de Nueva Orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de Nueva Orden','intro'=>'Nueva reservación Realizada','content'=>$content,)]);
                }else{

                    $success_process = executtePostOrderGiftcard(intval($parameters['Ds_Order']),1,$request);

                    $coupon=$success_process['coupon'];
                    $content=['order'=>intval($parameters['Ds_Order']),'coupon'=>$coupon];

                    $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $coupon->email,'subject' => 'Confirmación de pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de pago de orden','intro'=>'Felicidades su orden de cupon fue exitosa','content'=>$content,)]);
                    $confimail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.success_order','email_to' => $email_to,'subject' => 'Confirmación de Nueva Orden', 'sender'=>$sender, 'data' => array('title' => 'Confirmación de Nueva Orden','intro'=>'Nueva solicitud de cupon Realizada','content'=>$content,)]);

                }

                return  redirect()->route('homepage');
            } else {

                $typePayment = getTypePayment(intval($parameters['Ds_Order']));

                if($typePayment=="reservation"){

                    $success_process = executtePostOrder(intval($parameters['Ds_Order']),4,$request);
                    $reservation=$success_process['reservation'];
                    $content=['order'=>intval($parameters['Ds_Order'])];

                    emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$reservation->email],'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);
                }else{

                    $success_process = executtePostOrderGiftcard(intval($parameters['Ds_Order']),4,$request);

                    $coupon=$success_process['coupon'];
                    $content=['order'=>intval($parameters['Ds_Order'])];

                    emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$coupon->email],'subject' => 'Error en Pago de orden', 'sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

                }

                return  redirect()->route('homepage');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function ko(Requests $request)
    {

        $redsys_config = $this->paiament->where('name', 'Redsys')->first()->config;
        $collection = collect($redsys_config);
        $config = $collection->pluck('desc','label');
        $Ds_MerchantParameters = $request->input('Ds_MerchantParameters');
        $email_from = $this->setting->get('iforms::from-email');
        $email_to=explode(',',$this->setting->get('iforms::form-emails'));
        $sender  = $this->setting->get('core::site-name');

     try {
            $key = $config['key'];
            $parameters = Redsys::getMerchantParameters($Ds_MerchantParameters);
            $DsResponse = $parameters["Ds_Response"];
            $DsResponse += 0;
            if (Redsys::check($key, $request) && $DsResponse <= 99) {

                $typePayment = getTypePayment(intval($parameters['Ds_Order']));

                if($typePayment=="reservation"){


                    //Log::info('Error Reservacion Tipo 1 - '.time());

                    $success_process = executtePostOrder(intval($parameters['Ds_Order']),4,$request);
                    $reservation=$success_process['reservation'];
                    $content=['order'=>intval($parameters['Ds_Order'])];

                   $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$reservation->email],'subject' => 'Error en Pago de orden','sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);
                    //dd($mail);
                }else{

                    $success_process = executtePostOrderGiftcard(intval($parameters['Ds_Order']),4,$request);
                    $coupon=$success_process['coupon'];
                    $content=['order'=>intval($parameters['Ds_Order'])];

                   $mail= emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$coupon->email],'subject' => 'Error en Pago de orden','sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);
                    //dd($mail);

                }

                //dd($mail);

                return  redirect()->route('homepage');

            } else {

                $typePayment = getTypePayment(intval($parameters['Ds_Order']));

                if($typePayment=="reservation"){

                    //Log::info('Error Reservacion Tipo 2 - '.time());

                    $success_process = executtePostOrder(intval($parameters['Ds_Order']),4,$request);
                    $reservation=$success_process['reservation'];

                    $content=[
                        'order'=>intval($parameters['Ds_Order']),
                        'reservation' => $reservation
                    ];

                    $mail = emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$reservation->email],'subject' => 'Error en Pago de orden','sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

                }else{

                    $success_process = executtePostOrderGiftcard(intval($parameters['Ds_Order']),4,$request);
                    $coupon=$success_process['coupon'];

                    $content=[
                        'order'=>intval($parameters['Ds_Order']),
                        'coupon' => $coupon
                    ];

                    $mail = emailSend(['email_from'=>[$email_from],'theme' => 'ishoppingcart::email.error_order','email_to' => [$coupon->email],'subject' => 'Error en Pago de orden','sender'=>$sender, 'data' => array('title' => 'Error en Pago de orden','intro'=>'Ups... Algo ha salido mal','content'=>$content,)]);

                }

                //dd($mail);

                return redirect()->route('homepage');

            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}