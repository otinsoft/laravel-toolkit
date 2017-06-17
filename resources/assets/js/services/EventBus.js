import Vue from 'vue'

class EventBus {
  constructor () {
    this.bus = new Vue()
  }

  emit (name, ...args) {
    this.bus.$emit(name, ...args)

    return this
  }

  on (...args) {
    if (args.length === 2) {
      this.bus.$on(args[0], args[1])
    } else {
      Object.keys(args[0]).forEach(key => {
        this.bus.$on(key, args[0][key])
      })
    }

    return this
  }

  off (name, callback) {
    this.bus.$off(name, callback)
  }
}

const event = new EventBus()

export {
  EventBus,
  event as default
}
