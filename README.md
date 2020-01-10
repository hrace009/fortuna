#### Antes de prosseguir 📜

Este painel era um projeto privado, mas eu decidi trazê-lo "Open Source". Foi desenvolvido utilizando Laravel 6 e Vuejs 2, e é uma ["Single Page Application (SPA)"](https://en.wikipedia.org/wiki/Single-page_application)

### Instalação 👷‍♂️

**Requisitos 🧱**
- PHP >= 7.2.0 (bcmath, json, mbstring, pdo, openssl, xml)
- MySQL 5.7 (recomendado)
- Node.js (stable)
- Yarn

**Configurações & Comandos**

1. Renomeie o `.env.example` para `.env` (o arquivo possui instruções)

PS: Execute-os na ordem (e sem a #)

```sh
# composer install
# yarn
# php artisan jwt:secret
# php artisan key:generate
# php artisan migrate --seed
# yarn run dev
```

PS²: Aponte o painel para a pasta `public/`

**Algumas considerações ❗**

1. Este painel possui um sistema de "sub-contas", isto quer dizer que, o usuário cria uma conta ("conta mestre") e dentro do painel, pode criar "contas de jogo" (contas utilizadas para acessar o servidor em si.)

**Bugs 🦗**

Se desejar reportar um bug, crie uma issue e ele vai ser corrigido.