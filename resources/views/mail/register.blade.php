<x-mail::message>
Hello, {{ $profile->name }}.

You have registered a new account ! 

<x-mail::button :url="$url" color="success">
View your profile 
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
