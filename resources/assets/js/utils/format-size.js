/**
 * Format file size.
 *
 * @param  {Number} bytes
 * @return {String}
 */
export default bytes => {
  if (isNaN(bytes)) {
    return '0 B'
  }

  const quant = {
    GB: 1073741824,
    MB: 1048576,
    KB: 1024,
    B: 1
  }

  for (const unit in quant) {
    if (bytes >= quant[unit]) {
      const size = Math.round(bytes / quant[unit] * 10) / 10
      return `${size} ${unit}`
    }
  }

  return '0 B'
}
