@extends('layouts.default')
@section('content')
<form class="form-horizontal" method="post" action="{{ $paypal_conf['paypal_url'] }}" >
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Products</h2>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th width="280px">Image</th>
            <th >Price</th>
            <th >Payment</th>
        </tr>
<!-- Start: Paypal required varibales-->
    <input type="hidden" name="business" value="{{ $paypal_conf['business'] }}" />
    <input type='hidden' name='notify_url' value='{{ action('PaypalController@afterPayment') }}'>
    <input type='hidden' name='cancel_return' value='{{ action('PaypalController@cancelPayment') }}'>
    <input type='hidden' name='return' value='{{ action('PaypalController@successPayment') }}'>
    <input type="hidden" name="rm" value="{{ $paypal_conf['rm'] }}"/>
    <input type="hidden" name="no_shipping" value="{{ $paypal_conf['no_shipping'] }}"    />
    <input type="hidden" name="no_note" value="{{ $paypal_conf['no_note'] }}" />
    <input type="hidden" name="currency_code" value="{{ $paypal_conf['currency_code'] }}" />
    <input type="hidden" name="page_style" value="{{ $paypal_conf['page_style'] }}" />
    <input type="hidden" name="charset" value="{{ $paypal_conf['charset'] }}" />
    <input type="hidden" name="item_name" value="{{ $productarray['title'] }}" />
    <input type="hidden" value="_xclick" name="cmd"/>
    <input type="hidden" name="amount" value="{{ $productarray['price'] }}" />
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="item_number" value="{{ $productarray['id'] }}" />
<!-- End: Paypal required varibales-->
    <tr>
        <td>{{ $productarray['title'] }}</td>
        <td>{{ $productarray['description'] }}</td>
        <td><img style="height: 150px;width: 280px;"src ="{{URL::to( $productarray['image']) }}"></img> </td>       
        <td>${{ $productarray['price'] }}</td>
        <td>

          <div class="checkout-form" >
            <div class="form-group">
                <div class="">
                    <input style="margin-bottom:30px;" class="btn btn-primary" value="Pay with Paypal" type="submit" id="customsubmit">
                </div>
            </div>
        </div>
        </td>
    </tr>

 </table>
</form>

@endsection