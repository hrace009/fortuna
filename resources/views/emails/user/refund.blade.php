@component('mail::message')
# Olá {{ $order->owner->name }} 👋!

Em 2018, havíamos paralizado nossas atividades, e como talvez você já deve saber, 
estaremos retornando.

Vimos aqui, que você realizou algumas doações, e gostaríamos de te avisar: Vamos depositar os GOLDs em sua conta novamente. 🥳

Veja abaixo o valor total da sua contribuição e o quanto você irá receber: 

+ **Valor Total Contribuído:** {{ $order->formatMoney($cash['total_amount']) }}
+ **Valor Total em Cubi Gold**: {{ $order->formatAmount($cash['total_cash']) }}
+ **Conta de jogo**: {{ $order->game_login }}

__Caso tenha alguma dúvida sobre isso, basta clicar abaixo.__
@component('mail::button', ['url' => 'https://m.me/pw4fun?ref=reembolso'])
Eu estou com dúvidas!
@endcomponent

<img src="http://127.0.0.1:8000/campaigns/1/opens" alt="beacon">

Um abraço,<br>
Equipe {{ config('app.name') }}
@endcomponent
