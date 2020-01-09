@component('mail::message')
# Olá {{ split_name($order->owner->name)[0] }} 👋!

Seu pedido de doação n° **{{ $order->order_id }}**, via {{ __("app.order.gateways.{$order->gateway}") }}
foi identificado e adiantamos a entrega, acabamos de enviar **{{ $order->present()->cashFormatted }} GOLDs** para sua conta: __{{ $order->game_login }}__ 🎉 

**INFORMAÇÕES DO PEDIDO**
+ **Identificação:** {{ $order->order_id }}
+ **Login do jogo** {{ $order->game_login }}
+ **Valor**: {{ $order->present()->moneyFormatted }}
+ **GOLDs**: {{ $order->present()->cashFormatted }}
+ **Forma de Pagamento:** {{ __("app.order.gateways.{$order->gateway}") }}

Obrigado pela sua doação. Bom jogo! 👾

Um abraço,
Equipe {{ config('app.name') }}
@endcomponent
