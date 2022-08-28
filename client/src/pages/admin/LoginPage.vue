<template>
  <div class="row window-height window-width justify-center items-center">
    <div class="col" style="max-width: 400px">
      <q-card flat bordered class="my-card q-ma-sm">
        <q-card-section class="bg-primary text-white text-h5">
          님하 로그인하셈
        </q-card-section>

        <q-separator />

        <q-card-section>
          <q-form @submit="login" class="q-gutter-md">
            <q-input
              outlined
              v-model="email"
              type="email"
              label="이메일"
              pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
              hint="이메일을 입력하세요."
              lazy-rules
              :rules="[
                val => (val && val.length > 0) || '필수입력사항 입니다.'
              ]"
            />

            <q-input
              v-model="password"
              outlined
              :type="isPwd ? 'password' : 'text'"
              hint="비밀번호를 입력하세요."
              label="비밀번호"
              :rules="[
                val => (val && val.length > 0) || '필수입력사항 입니다.'
              ]"
            >
              <template v-slot:append>
                <q-icon
                  :name="isPwd ? 'mdi-eye-off' : 'mdi-eye'"
                  class="cursor-pointer"
                  @click="isPwd = !isPwd"
                />
              </template>
            </q-input>

            <q-toggle v-model="accept" label="운영정책 동의" />

            <div>
              <q-btn
                label="로그인"
                type="submit"
                color="primary"
                size="lg"
                class="full-width"
              />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
      <div>
        <q-btn label="GET USER" type="button" @click="getUser"></q-btn>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useUserStore } from 'stores/user'
import { SessionStorage } from 'quasar'
import { useRouter } from 'vue-router'

// const $store = useStore()
// layout: 'PublicLayout'
const userStore = useUserStore()
const router = useRouter()

const isPwd = ref(true)
const email = ref('')
const password = ref('')
const accept = ref(true)

// console.log(`Auth id: ${$store.state.auth.id}`)

const login = async () => {
  await userStore.getCsrfCookie()
  await userStore.login(email.value, password.value)
  const user = await userStore.fetchUser()
  userStore.setUser(user.data)
  if (user.data.id > 0) {
    router.push({ name: 'admin-dashboard' })
  }
}

const getUser = async () => {
  await userStore.fetchUser()
}

// 아래 코드는 부트로 옮긴다. 대시보드 만든다
const currentUser = SessionStorage.getItem('user')
if (currentUser !== null) {
  if (currentUser.id > 0) {
    console.log('dlkj')
    router.push({ name: 'admin-dashboard' })
  } else {
    console.log('뭔가 이상')
  }
}
</script>
