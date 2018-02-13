@component('mail::message')

Hey, {{ $reciever->name }}!

{{ $sender->name }} has shared a routine ({{ $routine->routine_name }}) with you!

@component('mail::button', ['url' => url('dashboard/my_routines')])
Have a look
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
