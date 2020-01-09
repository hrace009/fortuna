export default {
  methods: {
    filterUrl (data) {
      let url = ''
      data.forEach((key, value) => {
        url += (value) ? `&${key}=${encodeURI(value)}` : ''
      })
      return url
    }
  }
}
