import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  settings: null
}

// getters
export const getters = {
  settings: state => state.settings
}

// mutations
export const mutations = {
  [types.UPDATE_SETTINGS] (state, { settings }) {
    state.settings = settings
  },

  [types.FETCH_SETTINGS_SUCCESS] (state, { settings }) {
    state.settings = settings
  },

  [types.FETCH_SETTINGS_FAILURE] (state) {
    state.settings = null
  }

}

// actions
export const actions = {
  async fetchSettings ({ commit }) {
    try {
      const { data } = await axios.get('/api/admin/settings')

      commit(types.FETCH_SETTINGS_SUCCESS, { settings: data })
    } catch (e) {
      commit(types.FETCH_SETTINGS_FAILURE)
    }
  },

  updateSettings ({ commit }, payload) {
    commit(types.UPDATE_SETTINGS, payload)
  }

}
