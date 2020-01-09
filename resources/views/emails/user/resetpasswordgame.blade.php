@component('mail::message')
# Olá {{ $user->truename }}

Você está recebendo este e-mail porque recebemos um pedido de redefinição de senha para sua conta do jogo,
**({{ $user->name }})**.

@component('mail::button', ['url' => $url])
Redefinir Senha
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}

@component('mail::subcopy', ['url' => $url])
Se você está tendo problemas para clicar no botão de "Redefinir Senha", copie e cole o URL abaixo
no seu navegador [{{ $url }}]({{ $url }})
@endcomponent
@endcomponent
