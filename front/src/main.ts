import { useUserStore } from '@/stores/user'
import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import { createPinia } from "pinia"
import router from './router'
// Vuetify
import 'vuetify/styles'
import vuetify from './plugins/vuetify'

// WindyCSS
import 'virtual:windi.css'

// Mosha-toast-vue
import 'mosha-vue-toastify/dist/style.css'

const pinia = createPinia()
const app = createApp(App)

app.use(pinia)
app.use(vuetify)
app.use(router)
app.mount('#app')