@component('mail::message')
# Olá {{ $user->truename }}

Este é um email informativo, enviado pelo sistema do {{ config('app.name') }}. Por gentileza, não responda-o.

Estamos passando para lhe avisar que a senha da sua conta de jogo **({{ $user->name }})** foi alterada com sucesso, em {{ Carbon\Carbon::now()->format('d/m/Y à\s H:i:s') }}.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
