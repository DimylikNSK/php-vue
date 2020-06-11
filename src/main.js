import Vue from "vue";
import App from "./App.vue";
import axios from "axios";
import "./assets/bootstrap.min.css";

axios.defaults.baseURL =
  process.env.NODE_ENV !== "production" ? "http://localhost/" : "";
axios.defaults.headers.post["Content-Type"] = "application/json;charset=utf-8";
axios.defaults.headers.post["Access-Control-Allow-Origin"] = "*";
Vue.prototype.$http = axios;
Vue.config.productionTip = false;

new Vue({
  render: h => h(App)
}).$mount("#app");
