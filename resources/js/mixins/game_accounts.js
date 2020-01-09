
import axios from 'axios'

export default {
  data: () => ({
    accounts: [],
    selectedGameAccount: ''
  }),

  created () {
    this.fetch()
  },

  methods: {
    async fetch () {
      await axios.get('/api/game_accounts/all')
        .then((response) => {
          this.accounts = response.data.data
        })
    },
    getSelectedAccount (event) {
      this.selectedGameAccount = event.name
    }
  }
}
