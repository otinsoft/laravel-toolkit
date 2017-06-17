import format from 'date-fns/format'

export const DATE_FORMAT = 'MMM DD, YYYY'
export const DATE_TIME_FORMAT = 'MMM DD, YYYY @ HH:mm'

/**
 * Return the formatted date string in the given format.
 *
 * @param  {Date|String|Number} date
 * @param  {String} fmt
 * @return {String}
 */
export default (date, fmt = DATE_FORMAT) => format(date, fmt)
