@component('mail::message')
# Olá {{ $user->name }}

A autenticação de dois fatores foi ativada em sua conta! Woohool!

Isso significa que, ao fazer login na Central de Usuários, você também precisará inserir um código de segurança
gerado em seu celular, além do seu endereço de email e senha.


Atenciosamente,<br>
{{ setting('appName') }}
@endcomponent
