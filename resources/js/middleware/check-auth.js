import store from '~/store'

export default async (to, from, next) => {
  if (!store.getters['auth/check'] && store.getters['auth/token']) {
    try {
      await store.dispatch('auth/fetchUser')
      let user = store.getters['auth/user']
      if (user && (user.role == 'admin')) {
        await store.dispatch('system/fetchSettings')
      }       
    } catch (e) {}
  }

  next()
}
