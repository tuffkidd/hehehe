// import { useUserStore } from '../stores/user'
import { SessionStorage } from 'quasar'

export default function (store) {
  console.log(SessionStorage.getItem('token'))

  const routes = [
    {
      path: '/',
      component: () => import('layouts/MainLayout.vue'),
      children: [
        {
          path: '',
          name: 'home',
          component: () => import('pages/IndexPage.vue')
        }
      ]
    },
    {
      path: '/admin',
      name: 'admin',
      component: () =>
        SessionStorage.getItem('token')
          ? import('layouts/admin/DefaultLayout.vue')
          : import('layouts/admin/PublicLayout.vue'),
      children: [
        {
          path: 'login',
          name: 'admin-login',
          component: () => import('pages/admin/LoginPage.vue')
        },
        {
          path: 'recover',
          name: 'admin-recover',
          component: () => import('pages/admin/RecoverPage.vue')
        },
        {
          path: 'dashboard',
          name: 'admin-dashboard',
          component: () => import('pages/admin/DashboardPage.vue'),
          meta: {
            requiresAdminAuth: true
          }
        }
      ]
    },
    // Always leave this as last one,
    // but you can also remove it
    {
      path: '/:catchAll(.*)*',
      component: () => import('pages/ErrorNotFound.vue')
    }
  ]
  return routes
}
