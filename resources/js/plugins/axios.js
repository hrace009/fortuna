import axios from 'axios'
import store from '~/store'
import router from '~/router'
import swal from 'sweetalert2'

// Request interceptor
axios.interceptors.request.use(request => {
  const token = store.getters['auth/token']
  if (token) {
    request.headers.common['Authorization'] = `Bearer ${token}`
  }

  return request
})

// Response interceptor
axios.interceptors.response.use(response => response, error => {
  const { status } = error.response

  if (status === 500) {
    swal({
      type: 'error',
      title: 'Oops!',
      text: 'Algo deu errado. Tente novamente!',
      reverseButtons: true,
      confirmButtonText: 'OK',
      cancelButtonText: 'Cancelar',
    })
  }

  if (status === 401 && store.getters['auth/check']) {
    swal({
      type: 'warning',
      title: 'Sessão expirou',
      text: 'Sua sessão expirou, faça o login novamente.',
      reverseButtons: true,
      confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancelar',
    }).then(async () => {
      store.commit('auth/LOGOUT')

      router.push({ name: 'login' })
    })
  }
  return Promise.reject(error)
})
