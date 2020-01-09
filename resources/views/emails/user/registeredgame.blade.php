@component('mail::message')
# Olá {{ $user->truename }}

Este é um email informativo, enviado pelo sistema do {{ config('app.name') }}. Por gentileza, não responda-o.

Sua conta de jogo **{{ $user->name }}** foi criada com sucesso. Você já pode acessar o jogo!

Divirta-se.

@include('emails.signature')
@endcomponent
