<template>
  <div class="row">
    <div class="col-md-12">
      <portlet :title="'Meus Tickets'">
         <template v-if="!loading">
             <template v-if="!loading && items.length > 0">
                 <table class="table table-tickets m-table">
                     <thead>
                     <tr>
                         <th>Assunto</th>
                         <th>Status</th>
                         <th>Ultima atividade</th>
                         <th>Criado em</th>
                         <th>&nbsp;</th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr
                         v-for="ticket in items"
                         :key="ticket.id"
                     >
                         <td>
                             <div>
                                 <div class="mb--font-bolder d-flex align-items-center">
                                     <div class="no-wrap d-inline-block max-width-250">
                                         {{ ticket.subject }}
                                     </div>
                                     <span class="text-muted m--font-normal">#{{ ticket.id }}</span>
                                 </div>
                                 <div class="d-flex align-items-baseline">
                      <span class="m--regular-font-size-sm1 text-muted">
                        <div class="b-block">{{ ticket.category }}</div>
                      </span>
                                 </div>
                             </div>
                         </td>
                         <td>
                  <span
                      :class="`btn btn-bold btn-sm btn-font-sm  btn-label-${ticket.status.color}`"
                  >{{ ticket.status.name }}</span>
                         </td>
                         <td>
                             <span class="m--regular-font-size-sm1">{{ lastActivityText(ticket) }}</span>
                             <br>
                             <span v-tippy :title="ticket.updated_at | formatDate">
                                {{ ticket.updated_at | formatDateDistance }}
                             </span>
                         </td>
                         <td>
                             <div>
                                 <span>{{ ticket.created_at | formatDate  }}</span>
                             </div>
                         </td>
                         <td class="min-width-50">
                             <router-link
                                 :to="{ name: 'tickets.view', params: { id: ticket.id } }"
                                 :class="`btn btn-sm btn-light btn-icon btn-icon-md`"
                                 v-tippy="{ position: 'top' }"
                                 :title="`Ver ticket`"
                             >
                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--dark">
                                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                         <rect x="0" y="0" width="24" height="24"/>
                                         <path d="M8.29606274,4.13760526 L1.15599693,10.6152626 C0.849219196,10.8935795 0.826147139,11.3678924 1.10446404,11.6746702 C1.11907213,11.6907721 1.13437346,11.7062312 1.15032466,11.7210037 L8.29039047,18.333467 C8.59429669,18.6149166 9.06882135,18.596712 9.35027096,18.2928057 C9.47866909,18.1541628 9.55000007,17.9721616 9.55000007,17.7831961 L9.55000007,4.69307548 C9.55000007,4.27886191 9.21421363,3.94307548 8.80000007,3.94307548 C8.61368984,3.94307548 8.43404911,4.01242035 8.29606274,4.13760526 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                         <path d="M23.2951173,17.7910156 C23.2951173,16.9707031 23.4708985,13.7333984 20.9171876,11.1650391 C19.1984376,9.43652344 16.6261719,9.13671875 13.5500001,9 L13.5500001,4.69307548 C13.5500001,4.27886191 13.2142136,3.94307548 12.8000001,3.94307548 C12.6136898,3.94307548 12.4340491,4.01242035 12.2960627,4.13760526 L5.15599693,10.6152626 C4.8492192,10.8935795 4.82614714,11.3678924 5.10446404,11.6746702 C5.11907213,11.6907721 5.13437346,11.7062312 5.15032466,11.7210037 L12.2903905,18.333467 C12.5942967,18.6149166 13.0688214,18.596712 13.350271,18.2928057 C13.4786691,18.1541628 13.5500001,17.9721616 13.5500001,17.7831961 L13.5500001,13.5 C15.5031251,13.5537109 16.8943705,13.6779456 18.1583985,14.0800781 C19.9784273,14.6590944 21.3849749,16.3018455 22.3780412,19.0083314 L22.3780249,19.0083374 C22.4863904,19.3036749 22.7675498,19.5 23.0821406,19.5 L23.3000001,19.5 C23.3000001,19.0068359 23.2951173,18.2255859 23.2951173,17.7910156 Z" fill="#000000" fill-rule="nonzero"/>
                                     </g>
                                 </svg>
                             </router-link>
                         </td>
                     </tr>
                     </tbody>
                 </table>
                 <div class="col-lg-6 offset-lg-3 d-flex">
                     <pagination
                         :class="'mx-auto pagination-md'"
                         :data="dataSet.meta"
                         :limit="3"
                         @pagination-change-page="fetch"
                     />
                 </div>
             </template>
             <template v-else>
                 <div
                     class="m--align-center"
                 >
                     <img
                         src="https://pw4fun-painel.s3.sa-east-1.amazonaws.com/assets/svg/support.svg"
                         width="280px"
                         height="190px"
                     >
                     <p
                         class="m--padding-top-20 m--font-bold"
                     >
                         Você ainda não abriu nenhum ticket.
                     </p>
                 </div>
             </template>
         </template>
          <template v-else>
              <div class="d-flex justify-content-center">
                  <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--info"></div>
              </div>
          </template>        
      </portlet>
    </div>
  </div>
</template>

<script>
import collection from '~/mixins/collection';
import tickets from '~/mixins/tickets';

export default {
  middleware: 'auth',

  mixins: [collection, tickets],

  data: () => ({
    dataSet: false,
    button: true,
    loading: false,
  }),

  created() {
    this.fetch();
  },

  methods: {
    fetch(page) {
      this.loading = true;
      axios.get(this.url(page)).then(this.refresh);
    },
    url(page) {
      if (!page) {
        const query = location.search.match(/page=(\d+)/);
        page = query ? query[1] : 1;
      }

      return `/api/tickets?page=${page}`;
    },
    refresh({ data }) {
      this.loading = false;
      this.dataSet = data;
      this.items = data.data;
      window.scrollTo(0, 0);
    },
  },

  //
};
</script>

<style>
.table.table-tickets td {
  padding: 1.25rem;
}
</style>
