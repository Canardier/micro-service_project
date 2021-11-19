import Vue from "vue";
import {
  MdButton,
  MdContent,
  MdCheckbox,
  MdField,
  MdSnackbar,
  MdTabs,
  MdCard,
} from "vue-material/dist/components";
import "vue-material/dist/vue-material.min.css";
import "vue-material/dist/theme/default.css";
import "bootstrap/dist/css/bootstrap.css";

import VueMaterial from "vue-material";
import "vue-material/dist/vue-material.min.css";
import "vue-material/dist/theme/default.css";

Vue.use(VueMaterial);

export function getCookie(name) {
  const match = document.cookie.match(new RegExp(`(^| )${name}=([^;]+)`));

  if (match) return match[2];
  return null;
}

export function setCookie(name, value) {
  const myDate = new Date();
  myDate.setMonth(myDate.getMonth() + 12);
  document.cookie = `${name}=${value};expires=${myDate};domain=localhost;path=/`;
}

export function deleteAllCookies() {
  document.cookie.split(";").forEach(function(c) {
    document.cookie = `${
      c.trim().split("=")[0]
    }=;expires=Thu, 01 Jan 1970 00:00:00 UTC;domain=localhost;path=/`;
  });
}

Vue.use(MdButton);
Vue.use(MdCheckbox);
Vue.use(MdField);
Vue.use(MdContent);
Vue.use(MdTabs);
Vue.use(MdSnackbar);
Vue.use(MdCard);

export default ({ app }, inject) => {
  inject("setCookie", setCookie);
  inject("getCookie", getCookie);
  inject("deleteAllCookies", deleteAllCookies);
};
