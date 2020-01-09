@component('mail::message')
# Olá {{ split_name($order->owner->name)[0] }},


Melhor notícia do dia? Seu pedido de doação n° **{{ $order->order_id }}**, via {{ __("app.order.gateways.{$order->gateway}") }} foi **Aprovado**. Você acaba de receber ** {{ $order->present()->cashFormatted }} GOLDs**
na conta **{{ $order->game_login }}**.

**INFORMAÇÕES DO PEDIDO**
+ **Identificação:** {{ $order->order_id }}
+ **Login do jogo** {{ $order->game_login }}
+ **Valor**: {{ $order->present()->moneyFormatted }}
+ **Forma de Pagamento:** {{ __("app.order.gateways.{$order->gateway}") }}

Agradecemos por sua doação. Tenha um ótimo jogo! 🤗

Para mais detalhes sobre o pedido, acesse a Central de Jogador ou sua conta  {{ __("app.order.gateways.{$order->gateway}") }}.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
