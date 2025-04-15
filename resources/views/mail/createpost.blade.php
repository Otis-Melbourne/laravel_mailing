<x-mail::message>
# Introduction

{{ $post->name }} was created successfully.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
