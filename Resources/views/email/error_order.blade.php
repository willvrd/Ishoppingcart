@extends('email.plantilla')

@section('content')
    <table width="528" border="0" align="center" cellpadding="0" cellspacing="0"
           class="mainContent">
        <tbody>
        <tr>
            <td mc:edit="title1" class="main-header"
                style="color: #484848; font-size: 16px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                <multiline>
                    {{$data['title']}}
                </multiline>
            </td>
        </tr>
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td mc:edit="subtitle1" class="main-subheader"
                style="color: #a4a4a4; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                <multiline>
                    {{$data['intro']}}
                    <br><br>
                   
                    <div style="margin-bottom: 5px"><span
                                style="color: #484848;">Sr/Sra</span>

                        @if(isset($data['content']['reservation']))
                            @php $reservation=$data['content']['reservation']; @endphp

                            {{$reservation->customer->first_name}} {{$reservation->customer->last_name}}
                            
                        @endif
                        
                    </div>

                    <div style="margin-bottom: 5px"><span
                                style="color: #484848;">Su orden numero {{$data['content']['order']}}</span>
                    </div>

                    @if(isset($data['content']['reservation']))
                             @php $reservation=$data['content']['reservation']; @endphp
                    
                            <div style="margin-bottom: 5px"><span
                                    style="color: #484848;">Descripcion</span>
                            {!! $reservation->description !!}
                            </div>
                    @else

                             @php $coupon=$data['content']['coupon'] @endphp

                             <div style="margin-bottom: 5px"><span
                                    style="color: #484848;">Error Pedido Cupon</span>
                            </div>

                    @endif

                </multiline>
            </td>
        </tr>

        </tbody>
    </table>
@endsection