@component('mail::message')
# Olá {{ $user->association->owner->name }},

Este é um email informativo, enviado pelo sistema do {{ config('app.name') }}. Por gentileza, não responda-o.

Sua conta **({{ $user->name }})** foi associada com sucesso a conta **({{ $user->email }})**!

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
