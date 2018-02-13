@component('mail::message')

Hey, my dude!

Thanks for wanting to try out Logit. All you need to do now is to click the shiny button below to finish the registration process.

@component('mail::button', ['url' => '#', 'test' ])
GET IT DONE
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
