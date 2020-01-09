@component('mail::message')
# Olá {{ $user->name }}

Seja bem vindo ao <strong>{{ setting('appName') }}</strong>! Antes de prosseguir, você precisa confirmar seu cadastro, para começar a desfrutar de todos os recursos que nossa Central de Jogador tem à lhe oferecer.

**Mas calma aí!** Essa conta, é uma conta de gerenciamento, ela serve para unificar todas suas informações e contas de jogo. 
Após logar na Central, você poderá criar quantas contas quiser para acessar o servidor.

@component('mail::button', ['url' => $url])
Confirmar Cadastro
@endcomponent

@include('emails.signature')
@endcomponent
