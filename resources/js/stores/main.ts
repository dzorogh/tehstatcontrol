import { defineStore } from 'pinia';
import axios from 'axios';

export const useStore = defineStore('main', {
  state: () => {
    return {
      menuOpen: false,
      metrikaId: 86667607,
      groups: [],
    };
  },
  actions: {
    async getGroups() {
      const result = await axios.get('/api/stats/groups');
      this.groups = result.data.data;
    },
  },
});
