import Vue from 'vue'
import Vuex from 'vuex'
import constant from '@/constant/constant.js'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    displayType: constant.DISPLAY_TYPE.PC
  },
  getters: {
    getDisplayType(state) {
      return state.displayType
    }
  },
  mutations: {
    setDisplayType(state, type) {
      state.displayType = type;
    }
  },
  actions: {
  },
  modules: {
  }
})
