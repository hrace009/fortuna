<template>
  <div class="col-md-12">
    <portlet>
    <div class="m-portlet-body">
      <div class="m--align-center">
        <img
          src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/assets/svg/celebration-fans.svg"
          width="280px"
          height="190px"
        >
        <p class="m--padding-top-20 m--font-bold lead">Seu pedido foi recebido com sucesso!
          <br>Você receberá um e-mail em breve com mais informações.
        </p>
        <router-link
          class="btn m-btn--pill m-btn--air btn-brand m-btn--wide m--margin-top-5"
          :to="{ name: 'donate.history'}"
        >Histórico de Doações</router-link>
      </div>
    </div>
  </portlet>
  </div>
</template>

<script>
export default {
  middleware: "auth"
};
</script>