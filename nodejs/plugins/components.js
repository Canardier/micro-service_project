import Vue from "vue";
import {
  MdButton,
  MdContent,
  MdTabs,
  MdField,
  MdSnackbar
} from "vue-material/dist/components";
import "vue-material/dist/vue-material.min.css";
import "vue-material/dist/theme/default.css";
import "bootstrap/dist/css/bootstrap.css";

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
    window.location.href = "/";
  });
}

Vue.use(MdButton);
Vue.use(MdField);
Vue.use(MdContent);
Vue.use(MdTabs);
Vue.use(MdSnackbar);

export default ({ app }, inject) => {
  inject("setCookie", setCookie);
  inject("getCookie", getCookie);
  inject("deleteAllCookies", deleteAllCookies);
};
