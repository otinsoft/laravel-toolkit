import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

const { locale, messages } = window.config

export default new VueI18n({
  locale,
  messages: {
    [locale]: messages
  }
})
