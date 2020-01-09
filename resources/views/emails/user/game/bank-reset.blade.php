@component('mail::message')
#   Olá 

Você restá recebendo esse email porque recebemos um pedido de redefinição de senha do banqueiro, do personagem **{{ $storehouse->role->name }}** na conta
**{{ $storehouse->game_account['name'] }}**.

Para confirmar, basta clicar no botão abaixo. Lembre-se, **você precisa estar logado na Central de Jogador** e ter certeza de que seu personagem está **deslogado** do jogo.

@component('mail::button', ['url' => $url])
Confirmar Redefinição de Senha do Banqueiro
@endcomponent

Caso não tenha sido você, não tem problema, basta ignorar este email!

Atenciosamente,<br>
{{ config('app.name') }}

@component('mail::subcopy', ['url' => $url])
Se você está tendo problemas para clicar no botão de "Confirmar Redefinição de Senha do Banqueiro", copie e cole o URL abaixo
no seu navegador [{{ $url }}]({{ $url }})
@endcomponent
@endcomponent
