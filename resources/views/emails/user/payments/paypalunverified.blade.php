@component('mail::message')
# Olá {{ $order->owner->name }},

Houve um problema no seu pedido **#{{ $order->order_id }}** via {{ payment_gateway($order->gateway) }}.

Aparentemente, sua conta do PayPal não é verificada e por razões de segurança, não poderemos entregar o seu Cubi Gold.
Isto ocorre porque, ainda que você tenha confirmado seus emails, telefones e até mesmo cartões de crédito, o PayPal pode continuar marcando sua conta como
**"não verificada"** até que você vincule uma conta corrente a sua conta do PayPal.

Caso sua conta do PayPal não seja verificada, basta fazer o seguinte:

- Faça login de sua conta do PayPal - (https://www.paypal.com)[(https://www.paypal.com];
- Vá em "Adicionar Seu Banco";
- Preencha os dados da sua conta;
- O PayPal depositará em sua conta bancária 2 pequenos valores (alguns centavos);
- Assim que receber os dois valores do PayPal, volte para sua conta do PayPal e informe os dois valores depositados para concluir a verifcação de sua conta.

Caso ainda tenha dificuldades de verificar sua conta do PayPal em seu país, é recomendável entrar em contato com o atendimento do PayPal (https://www.paypal.com/selfhelp/home)[https://www.paypal.com/selfhelp/home]
para que possam ajudá-lo nesse processo.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
