<template>
 <div class="col-md-3">
      <portlet :title="'Detalhes do ticket'" :class="'m-portlet m-portlet--head-sm'">
    <template v-if="loading">
      <div class="m-loader m-loader--brand m--padding-10"></div>
    </template>
    <template v-else>
      <ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
        <!-- User -->
        <slot name="extra"></slot>
        <!-- Opened at -->
        <li class="m-nav__item">
          <div class="m-nav__link m--display-flex-center">
            <span class="m-nav__link-title ml-15">
              <span class="m-nav__link-wrap">
                <span class="m-nav__link-text">
                  <small>Aberto em</small>
                  <br />
                  {{ ticket.created_at.date }} at {{ ticket.created_at.time }}
                </span>
              </span>
            </span>
          </div>
        </li>
        <!-- Status -->
        <li class="m-nav__item">
          <div class="m-nav__link m--display-flex-center">
            <span class="m-nav__link-title ml-15">
              <span class="m-nav__link-wrap">
                <span class="m-nav__link-text">
                  <small>Status</small>
                  <br />
                  <span
                    class="m-badge m-badge--wide m-badge--rounded"
                    :class="`m-font--white m-badge--${ticket.status.color}`"
                  >
                    {{
                    ticket.status.name }}
                  </span>
                </span>
              </span>
            </span>
          </div>
        </li>
        <!-- Updated at -->
        <li class="m-nav__item">
          <div class="m-nav__link m--display-flex-center">
            <span class="m-nav__link-title ml-15">
              <span class="m-nav__link-wrap">
                <span class="m-nav__link-text">
                  <small>Última atualização</small>
                  <br />
                  <span :title="ticket.updated_at">{{ ticket.updated_at }}</span>
                </span>
              </span>
            </span>
          </div>
        </li>
        <!-- Category -->
        <li class="m-nav__item">
          <div class="m-nav__link m--display-flex-center">
            <span class="m-nav__link-title ml-15">
              <span class="m-nav__link-wrap">
                <span class="m-nav__link-text">
                  <small>Categoria</small>
                  <br />
                  <span>{{ ticket.category }}</span>
                </span>
              </span>
            </span>
          </div>
        </li>
        <slot name="actions"></slot>
      </ul>
    </template>
  </portlet>
 </div>
</template>

<script>
export default {
  name: "TicketDetails",
  props: ["ticket", "loading"]
};
</script>
