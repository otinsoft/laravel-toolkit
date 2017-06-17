import Vue from 'vue'
import formatDate from '../utils/format-size'

Vue.filter('formatSize', val => val ? formatSize(val) : '')
