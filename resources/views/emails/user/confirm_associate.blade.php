@component('mail::message')
# Olá {{ $user->association->owner->name }}

Este é um email informativo, enviado pelo sistema do {{ setting('appName') }}. Por gentileza, não responda-o.

Você está recebendo esse e-mail pois solicitiou que a conta de jogo **{{ $user->name }}** seja associada a conta mestre **{{ $user->association->owner->email }}**;

Para prosseguir, basta inserir o token de confirmação na página de associação de contas:
**{{ $user->association->token }}**

Caso você não tenha sido você que solicitou, não se preocupe, basta ignorar este e-mail.

**Atenciosamente,**<br>
{{ setting('appName') }}
@endcomponent
