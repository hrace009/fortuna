@component('mail::message')
# Olá {{ $order->owner->name }},


Houve um problema no seu pedido n° **{{ $order->order_id }}**, via {{ __("app.order.gateways.{$order->gateway}") }}.

Recebemos a notificação que seu pagamento foi **{{ $payment->present()->statusLabel }}**. Mas parece que o sistema não conseguiu realizar a entrega automática dos seus GOLDs. Isso
pode ter acontecido por conta de alguma instabilidade. Mas não se preocupe, seu pedido agora está com status **Em revisão**, e assim que o problema for solucionado
lhe enviaremos um novo e-mail.

**INFORMAÇÕES DO PEDIDO**
+ **Identificação:** {{ $order->order_id }}
+ **Servidor**:  {{ config('app.name') }}
+ **Login do jogo** {{ $order->game_account['name'] }}
+ **Valor**: {{ $order->amount }}
+ **Forma de Pagamento:** {{ __("app.order.gateways.{$order->gateway}") }}

Agradecemos por sua doação e pedimos desculpas pelo transtorno.

Para mais detalhes sobre o pedido, acesse a Central de Jogador ou sua conta {{ __("app.order.gateways.{$order->gateway}") }}
@component('mail::button', ['url' => url('/') ])
VER MEUS PEDIDOS
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
