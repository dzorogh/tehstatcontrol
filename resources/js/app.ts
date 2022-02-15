import { createApp, getCurrentInstance } from 'vue';
import App from './components/App.vue';
import router from './router';
import { store, key } from './store';
import { AxiosError, default as axios } from 'axios';
import { setSuffix } from './title';

const app = createApp(App);

app.use(router);
app.use(store, key);

const mounted = app.mount('#app');

declare const window: any;

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
      console.log('[metrika: userParams]', store.state.metrikaId, {
        email: email,
        UserId: email
      });
      
      window.ym(store.state.metrikaId, 'userParams', {
        email: email,
        UserId: email
      });
    }
    
    console.log('[metrika: hit]', store.state.metrikaId, to.path);
    window.ym(store.state.metrikaId, 'hit', to.path);
  }
  
  document.title = store.state.title;
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

setSuffix(' - ГЦТИ');
