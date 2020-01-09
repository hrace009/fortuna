
import Vue from 'vue';

Vue.filter('currency', (value) => {
    return Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
})

Vue.filter('gold', (value) => {
    return  Intl.NumberFormat('pt-BR', { style: 'decimal' }).format(value);
})