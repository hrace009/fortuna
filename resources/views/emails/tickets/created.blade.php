@component('mail::message')
# Olá {{ $ticket->owner->name }}

Seu ticket de suporte "{{ $ticket->subject }}" (#{{ $ticket->track_id }}) foi recebido.

{{ $ticket->message }}

Conte conocso sempre que precisar, estamos à sua disposição.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
