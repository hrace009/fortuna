@component('mail::message')
# Olá {{ $order->owner->name }},

O pagamento do seu pedido feito via {{ $order->gateway }}, de n° #{{ $order->order_id }} não foi aprovado. :'(

Tudo não está perdido! Você sempre pode tentar de novo, com outro meio de pagamento. Afinal, a persistência é o caminho do sucesso.

Dê uma olhada em seus pedidos e tente de novo!
@component('mail::button', ['url' => url('doacao/historico-de-doacoes')])
VER MEUS PEDIDOS
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}

@component('mail::subcopy', ['url' => url('doacao/historico-de-doacoes')])
Se você está tendo problemas para clicar no botão de "VER MEUS PEDIDOS", copie e cole o URL abaixo
no seu navegador [{{ url('doacao/historico-de-doacoes') }}]({{  url('doacao/historico-de-doacoes') }})
@endcomponent
@endcomponent
