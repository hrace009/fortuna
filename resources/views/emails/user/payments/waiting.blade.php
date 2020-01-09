@component('mail::message')
# Olá {{ $order->owner->name }},

Recebemos o seu pedido!

Estamos aguardando a confirmação do seu pagamento, via {{ __("app.order.gateways.{$order->gateway}") }}, do Pedido de Doação ID #**#{{ $order->order_id }}**.

Pode ficar tranquilo. Assim que isso acontecer, vamos te mandar um email automagicamente.

Se preferir, dê uma olhada nos seus pedidos
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
