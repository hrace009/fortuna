function page (path) {
    return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', redirect: { name: 'login' } },

  // Authenticated routes.

  { path: '/home', name: 'home', component: page('home.vue'), meta: { title: 'Home' } },
  {
    path: '/minha-conta',
    component: page('settings/index.vue'),
    meta: { title: 'Minha Conta' },
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'meus-dados', name: 'settings.profile', component: page('settings/profile.vue'), meta: { title: 'Minha Conta' } },
      { path: 'alterar-senha', name: 'settings.password', component: page('settings/password.vue'), meta: { title: 'Minha Conta' } },
      { path: 'seguranca', name: 'settings.security', component: page('settings/security.vue'), meta: { title: 'Minha Conta' } },
      { path: 'contas-vinculadas', name: 'game.accounts', component: page('game/index.vue'), meta: { title: 'Minha Conta' } },
      { path: 'contas-vinculadas/alterar-senha-jogo/:account', name: 'game.password', component: page('game/password.vue'), meta: { title: 'Minha Conta' } },
    ]
  },
  { path: '/suporte', name: 'tickets', component: page('tickets/index.vue'), meta: { title: 'Suporte', breadcrumb: 'Meus tickets', link: { title: 'Novo Ticket', icon: 'flaticon-chat-1', name: 'tickets.create' } } },
  { path: '/suporte/criar_ticket', name: 'tickets.create', component: page('tickets/create.vue'), meta: { title: 'Novo Ticket' } },
  { path: '/suporte/visualizar_ticket/:id', name: 'tickets.view', component: page('tickets/show.vue'), meta: { title: 'Ticket' } },
  {
    path: '/doacao',
    name: 'donate',
    component: page('donate/index.vue'),
    redirect: { name: 'donate.make' },
    meta: { title: 'Doações' },
    children: [
      { path: 'fazer-doacao', name: 'donate.make', component: page('donate/request.vue'), meta: { title: 'Doações' } },
      { path: 'historico-de-doacoes', name: 'donate.history', component: page('donate/history.vue'), meta: { title: 'Histórico de Doações' } },
    ]
  },
  // Guest routes.
  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/email/confirmar/:id', name: 'email.confirm', component: page('auth/login.vue') },
  { path: '/cadastro', name: 'register', component: page('auth/register.vue') },
  { path: '/esqueci-a-senha', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/esqueci-a-senha/:token', name: 'password.reset', component: page('auth/password/reset.vue') },

  { path: '*', redirect: { name: 'home' } }
]
