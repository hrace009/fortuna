import Vue from 'vue';
import { format, formatDistance } from 'date-fns'
import { ptBR } from 'date-fns/esm/locale'

Vue.filter('formatDate', (date) => {
   return format(new Date(date), 'iii, dd MMMM, Y', {
       locale: ptBR
   })
});

Vue.filter('formatDateWithHours', (date) => {
    return format(new Date(date), "iii, dd MMMM, Y", {
        locale: ptBR
    })
});

Vue.filter('formatDateDistance', (date) => {
    return formatDistance(new Date(date), new Date(), {
        addSuffix: true,
        locale: ptBR
    })
});