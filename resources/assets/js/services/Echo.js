import Echo from 'laravel-echo'

let echo = null

const { pusher, csrfToken } = window.config

if (window.Pusher && pusher.key) {
  echo = new Echo({
    csrfToken,
    broadcaster: 'pusher',
    key: pusher.key,
    ...pusher.options
  })
}

export default echo
