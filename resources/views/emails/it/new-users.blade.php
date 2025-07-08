<x-mail::message>
# Novo utilizador registado na plataforma

O utilizador {{ $user->name }} foi registado na plataforma é preciso validar.

<x-mail::button :url="''">
Visitar
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
