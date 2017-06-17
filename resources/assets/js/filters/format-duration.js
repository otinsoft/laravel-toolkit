import Vue from 'vue'
import formatDate from '../utils/format-duration'

Vue.filter('formatDuration', val => val ? formatDuration(val) : '')
