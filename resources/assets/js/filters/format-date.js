import Vue from 'vue'
import formatDate from '../utils/format-date'

Vue.filter('formatDate', (val, format) => val ? (format ? formatDate(val, format) : formatDate(val)) : '')
