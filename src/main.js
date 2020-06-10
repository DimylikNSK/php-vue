import Vue from "vue";
import App from "./App.vue";
import axios from "axios";
import "./assets/bootstrap.min.css";

axios.defaults.baseURL =
  process.env.NODE_ENV !== "production" ? "http://phptest" : "";
Vue.prototype.$http = axios;
Vue.config.productionTip = false;

new Vue({
  render: h => h(App)
}).$mount("#app");
