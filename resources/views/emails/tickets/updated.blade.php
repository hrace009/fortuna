@component('mail::message')
# Olá {{ $reply->ticket->owner->name }},

Nossa equipe acabou de responder o seu ticket **{{ $reply->track_id }}**:

_{{ $reply->owner->name }} respondeu:_

{{ $reply->message }}

Para atualizar ou verificar o progresso do seu ticket, visite o Painel de Jogador.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
