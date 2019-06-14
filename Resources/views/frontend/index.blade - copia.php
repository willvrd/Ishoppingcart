
@if(Session::get('reservationID'))

    @php
        $reservationID=Session::get('reservationID');
    @endphp

    @push('reservation')
        {{Form::hidden('reservationID', $reservationID)}}
    @endpush

@else

    <script type="text/javascript">
        window.location = "{{url('')}}";
    </script>

@endif

{{--
@if(Session::get('reservation'))

@php
$reservation=Session::get('reservation');
@endphp

    @push('reservation')
    {{Form::hidden('reservationID', $reservationID)}}
    {{--
    {{Form::hidden('fullname', $reservation->fullname)}}
    {{Form::hidden('email', $reservation->email)}}
    {{Form::hidden('phone', $reservation->phone)}}
    {{Form::hidden('event_slot_id', $reservation->event_slot_id)}}

    {{Form::hidden('date', date($reservation->date, 'Y-m-d'))}}
    {{Form::hidden('date', date('Y-m-d',$reservation->date))}}
    {{Form::hidden('date', $reservation->date->format('Y-m-d'))}}
    {{Form::hidden('date', $reservation->date)}}

    {{Form::hidden('coupon_id', $reservation->coupon_id)}}
    {{Form::hidden('value', $reservation->value)}}
    {{Form::hidden('status', $reservation->status)}}
    
    @endpush

@endif
--}}

@extends('layouts.master')

@section('title')
    {{trans('ishoppingcart::common.uri')}} | @parent
@stop

@section('content')

<div class="page ishopping ishopping-index">
<div class="container">

    <div class="row">
        <div class="col-xs-12">
            <h1>{{trans('ishoppingcart::frontend.index.title')}}</h1>
        </div>
    </div>

    </br>

    <div class="row category-body-1 column1" style="font-family: 'Calibri'; "> 

        @if (!empty($payments))
            @php $cont = 0; @endphp

            @foreach($payments as $payment)

                <div class="col-xs-12">
                    <div class="panel panel-default payment payment-id-{{$payment->id}}">

                        <div class="panel-heading">
                            <h2 class="panel-title">{{$payment->name}}</h2>
                        </div>

                        <div class="panel-body"> {!!$payment->description!!}</div>

                            {{Form::open(array('route' => 'ishoppingcart.checkout_process', 'method' => 'post'))}}
                            {{--
                            {{Form::open(array('url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'method' => 'post'))}}

                                
                                <input name="cmd" type="hidden" value="_cart" /> 
                                <input name="business" type="hidden" value="" />
                                <input name="shopping_url" type="hidden" value="{{url('')}}" />
                                <input name="currency_code" type="hidden" value="EUR" />

                                <input name="return" type="hidden" value="{{route('ishoppingcart.paypal_msj')}}" />

                                <input name="notify_url" type="hidden" value="{{route('ishoppingcart.paypal_ipn')}}" />

                                <input name="rm" type="hidden" value="2" />
                                @php
                                    $itemName = $reservation->fullname ."-".$reservation->event_slot_id;
                                @endphp    
                                <input name="item_number_1" type="hidden" value="" />
                                <input name="item_name_1" type="hidden" value="{{$itemName}}" /> 
                                <input name="amount_1" type="hidden" value="{{$reservation->value}}" /> 
                                <input name="quantity_1" type="hidden" value="1" />
                                 --}}

                                @stack('reservation')

                                <input name="paymentID" type="hidden" value="{{$payment->id}}" />

                                <input type="submit" class="btn btn-primary btn-payment" value="{{trans('ishoppingcart::frontend.index.button')}}">

                               

                            {{Form::close()}}
                       
                        {{--
                        @if($payment->id==1)

                            {{Form::open(array('url' => 'https://sis-t.redsys.es:25443/sis/realizarPago', 'method' => 'post'))}}
                                <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1"/>
                                <input type="hidden" name="Ds_MerchantParameters" value=" eyJEU19NRVJDSEFOVF9BTU9VTlQiOiIxNDUiLCJEU19NRVJDSEFOVF9PUkRFUiI6IjE0NDY wNjg1ODEiLCJEU19NRVJDSEFOVF9NRVJDSEFOVENPREUiOiI5OTkwMDg4ODEiLCJEU19N RVJDSEFOVF9DVVJSRU5DWSI6Ijk3OCIsIkRTX01FUkNIQU5UX1RSQU5TQUNUSU9OVFlQ RSI6IjAiLCJEU19NRVJDSEFOVF9URVJNSU5BTCI6IjEiLCJEU19NRVJDSEFOVF9NRVJDSEF OVFVSTCI6Imh0dHA6XC9cL3d3dy5wcnVlYmEuY29tXC91cmxOb3RpZmljYWNpb24ucGh wIiwiRFNfTUVSQ0hBTlRfVVJMT0siOiJodHRwOlwvXC93d3cucHJ1ZWJhLmNvbVwvdXJsT0 sucGhwIiwiRFNfTUVSQ0hBTlRfVVJMS08iOiJodHRwOlwvXC93d3cucHJ1ZWJhLmNvbVwvd XJsS08ucGhwIiwiRFNfTUVSQ0hBTlRfUEFOIjoiNDU0ODgxMjA0OTQwMDAwNCIsIkRTX01 FUkNIQU5UX0VYUElSWURBVEUiOiIxNTEyIiwiRFNfTUVSQ0hBTlRfQ1ZWMiI6IjEyMyJ9"/>
                                <input type="hidden" name="Ds_Signature" value="PqV2+SF6asdasMjXasKJRTh3UIYya1hmU/igHkzhC+R="/>

                                <input type="submit" class="btn btn-primary btn-payment" value="{{trans('ishoppingcart::frontend.index.button')}}">

                                 @stack('reservation')

                            {{Form::close()}}

                        @endif
                        --}}

                    </div> {{-- panel --}} 
                </div>

            @endforeach

            <div class="clearfix"></div>

            <div class="pagination pagination-payment row">
                <div class="pull-right">{{$payments->links()}}</div>
            </div>
                    
        @endif

    </div> {{-- row column 1--}} 

</div>
</div>



@stop