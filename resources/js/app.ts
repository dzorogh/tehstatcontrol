import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import { AxiosError, default as axios } from 'axios';
import { setSuffix, setTitle } from './title';
import { createPinia } from 'pinia'
import { useStore } from './stores/main';


setSuffix(' - ГЦТИ');

const app = createApp(App);

app.use(router);
app.use(createPinia())

const mounted = app.mount('#app');

declare const window: any;

const store = useStore();

router.afterEach((to) => {
  let email = to.query.email;
  
  if (email) {
    localStorage.setItem('email', email as string);
    router.replace({
      ...to,
      query: {},
    });
  } else {
    email = localStorage.getItem('email');
  }
  
  if (window.ym) {
    if (email) {
      console.log('[metrika: userParams]', store.metrikaId, {
        email: email,
        // UserID: email
      });
      
      window.ym(store.metrikaId, 'userParams', {
        email: email,
        // UserID: email
      });
    }
    
    console.log('[metrika: hit]', store.metrikaId, to.path);
    window.ym(store.metrikaId, 'hit', to.path);
  }
});

router.beforeEach(async (to, from) => {
  
  if (to.name === 'login') {
    return true;
  }
  
  try {
    const result = await axios.get('/api/check-auth');
  } catch (exception) {
    const error = exception as AxiosError;
    
    if (error.response!.status === 401) {
      await router.push({
        path: '/login',
        replace: true,
      });
    }
    
    return false;
  }
});
