// import router from ''
import axios from 'axios'
import vue from 'vue'


let edaAxios = axios.create({
  timeout: 90 * 1000,
  withCredentials: true,
})


vue.prototype.$Connect =
  (paramUrl, paramMethod, data = {}, params = {}, headers = {}) => {
    return new Promise((resolve, reject) => {
      edaAxios({
        method: paramMethod,
        url: `${process.env.VUE_APP_API_URL}${paramUrl}`,
        params: params,
        data: data,
        headers: headers
      }).then(res => {

        if (res.data.success) {

          resolve(res.data.body)
        } else {

          reject(res.data.errMsg)
        }
      }).catch(error => {

        window.alert(error)
      })
    })
  }

  export default edaAxios

