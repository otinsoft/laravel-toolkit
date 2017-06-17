/**
 * Format duration.
 *
 * @param  {Number} seconds
 * @return {String}
 */
export default seconds => {
  let minutes = Math.floor(seconds / 60)
  let seconds = seconds - (minutes * 60)

  if (minutes < 10) minutes = `0${minutes}`
  if (seconds < 10) seconds = `0${seconds}`

  return `${minutes}:${seconds}`
}
