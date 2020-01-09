import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  user: null,
  token: Cookies.get('token'),
  twoFactorToken: null
}

// getters
export const getters = {
  user: state => state.user,
  token: state => state.token,
  check: state => state.user !== null,
  twoFactorToken: state => state.twoFactorToken
}

// mutations
export const mutations = {
  [types.SAVE_TOKEN] (state, { token, remember }) {
    state.token = token
    Cookies.set('token', token, { expires: remember ? 365 : null })
  },

  [types.SAVE_TWOFACTOR_TOKEN] (state, { twoFactorToken }) {
    state.twoFactorToken = twoFactorToken
    Cookies.set('twofactor_token', twoFactorToken, { expires: null })
  },

  [types.REMOVE_TWOFACTOR_TOKEN] (state) {
    state.twoFactorToken = null
    Cookies.remove('twofactor_token')
  },

  [types.FETCH_USER_SUCCESS] (state, { user }) {
    state.user = user
  },

  [types.FETCH_USER_FAILURE] (state) {
    state.token = null
    Cookies.remove('token')
  },

  [types.LOGOUT] (state) {
    state.user = null
    state.token = null
    Cookies.remove('token')
  },

  [types.UPDATE_USER] (state, { user }) {
    state.user = user
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  saveTwoFactorToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TWOFACTOR_TOKEN, payload)
  },

  removeTwoFactorToken ({ commit }) {
    commit(types.REMOVE_TWOFACTOR_TOKEN)
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      commit(types.FETCH_USER_SUCCESS, { user: data.data })
    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) {}

    commit(types.LOGOUT)
  },

  async fetchOauthUrl (ctx, { provider }) {
    const { data } = await axios.post(`/api/oauth/${provider}`)

    return data.url
  }
}
