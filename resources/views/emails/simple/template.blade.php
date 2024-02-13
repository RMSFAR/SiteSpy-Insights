@component('mail::message')
# {{$name}}

{!! $message !!}

Thanks,<br>
{{ config('my_config.product_name') }} Team
@endcomponent