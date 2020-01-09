@component('mail::message')
# Olá {{ $user->name }}

Este é um email informativo, enviado pelo sistema do {{ config('app.name') }}. Por gentileza, não responda-o.

A senha da sua conta ({{ $user->email }}) foi alterada em {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}.

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
