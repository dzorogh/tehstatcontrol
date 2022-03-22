import { defineStore } from 'pinia';
import axios from 'axios';

export const useStore = defineStore('main', {
  state: () => {
    return {
      menuOpen: false,
      metrikaId: 86667607,
      groups: [],
      compare: JSON.parse(localStorage.getItem('compare')),
    };
  },
  actions: {
    async getGroups() {
      const result = await axios.get('/api/stats/groups');
      this.groups = result.data.data;
    },
    
    toggleCompare(id) {
      this.compare[id] = !this.compare[id];
      
      localStorage.setItem('compare', JSON.stringify(this.compare));
    },
  },
  getters: {
    compareIds: (store) => {
      return Object.keys(
        Object.fromEntries(
          Object.entries(store.compare)
            .filter((item) => {
              return item[1];
            })
        )
      );
    },
  },
});
