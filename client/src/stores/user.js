import { defineStore } from 'pinia'
import { api } from 'boot/axios'
import { SessionStorage } from 'quasar'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: SessionStorage.getItem('user') || {},
    token: SessionStorage.getItem('token') || ''
  }),
  actions: {
    async getCsrfCookie () {
      try {
        await api.get('/v1/sanctum/csrf-cookie')
      } catch (error) {
        if (error) throw error
      }
    },
    async login (email, password) {
      try {
        const { data } = await api.post('/v1/auth/login', { email, password })
        this.setToken(data)
        // await api.post('/v1/auth/login', { email, password }).then(response => {
        //   api.defaults.headers.common.Authorization =
        //     'Bearer ' + response.data.token
        // })
      } catch (error) {
        if (error) throw error
      }
    },
    async fetchUser () {
      try {
        return await api.get('/v1/auth/user')
      } catch (error) {
        if (error) throw error
      }
    },
    setToken (loginData) {
      const copyOfData = { ...loginData }
      SessionStorage.set('token', copyOfData.token)
      this.token = copyOfData.token
    },
    setUser (userData) {
      const copyOfData = { ...userData }
      SessionStorage.set('user', copyOfData)
      this.user = copyOfData
    },
    getUser () {
      return this.user
    }
    // clearUser () {
    //   SessionStorage.set('token', {})
    //   SessionStorage.set('user', {})
    //   this.user = {}
    // }
  }
})
