import { boot } from 'quasar/wrappers'
import { createQuasarWebStoragePersistedState } from 'pinia-plugin-persistedstate/quasar'
import { LocalStorage } from 'quasar'

export default boot(({ app, router, store }) => {
  store.use(createQuasarWebStoragePersistedState(LocalStorage))
  // store.use(createQuasarCookiesPersistedState())
})
