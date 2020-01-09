
import * as types from '../mutation-types'

// state
export const state = {
  order: null
}

// getters
export const getters = {
  order: state => state.order
}

// mutations
export const mutations = {
  [types.UPDATE_ORDER] (state, { order }) {
    state.order = order
  }
}

// actions
export const actions = {
  updateOrder ({ commit }, payload) {
    commit(types.UPDATE_ORDER, payload)
  }
}
