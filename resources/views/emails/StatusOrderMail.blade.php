@component('mail::message')
# @lang('mail.order_status.Introduction')
<h2 style="text-align: center">@lang('mail.order_status.h2' , ['old' => $oldStatus , 'newStatus' => $order->status])</h2>

@component('mail::table')
|            @lang('mail.order_status.row2')           	|      @lang('mail.order_status.row1')|
|:----------------------------:	|:-------------------:	                        |
|   @lang("aliases.status.$order->status")          |     @lang('mail.order_status.status') |
|   {{$order->products_count}}  |     @lang('mail.order_status.products_count') |
|    {{$order->order_price}}   	|     @lang('mail.order_status.order price')    |
|    {{$order->discount}}   	|     @lang('mail.order_status.discount')       |
|  {{$order->shipping_price}}  	|     @lang('mail.order_status.shipping price') |
|       {{$order->total_price}} |     @lang('mail.order_status.total')          |
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
