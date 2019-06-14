@if(isset($request))

    @push('inforGiftcard')
        {{Form::hidden('email', $request->email)}}
        {{Form::hidden('eventid', $request->eventid)}}
        {{Form::hidden('price', $request->price)}}
    @endpush

@else

    <script type="text/javascript">
        window.location = "{{url('')}}";
    </script>

@endif



@extends('layouts.master')

@section('title')
    {{trans('ishoppingcart::common.uri')}} | @parent
@stop

@section('content')

<div class="page ishopping ishopping-index">
<div class="container">

    <div class="row">
        <div class="col-xs-12">
            @php
            
            if(!empty($payments))
                $count = $payments->count();
            else 
                $count = 1;
            
            @endphp
            <h1>{{trans_choice('ishoppingcart::frontend.index.title',['count' => $count])}}</h1>
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

                            {{Form::open(array('route' => 'ishoppingcart.giftcard.checkout_process', 'method' => 'post'))}}
                            
                                <input name="paymentID" type="hidden" value="{{$payment->id}}" />

                                @stack('inforGiftcard')

                                <input type="submit" class="btn btn-primary btn-payment" value="{{trans('ishoppingcart::frontend.index.button')}}">

                            {{Form::close()}}
                       
                       
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