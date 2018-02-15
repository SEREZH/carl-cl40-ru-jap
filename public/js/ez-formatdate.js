/**
 * Format a date like YYYY-MM-DD.
 *
 * @param {string} template
 * @param {Date=} [date]
 * @return {string}
 * @license MIT
 */
function formatDate(template, date) {
  var specs = 'YYYY:MM:DD:HH:mm:ss'.split(':');
  date = new Date(date || Date.now() - new Date().getTimezoneOffset() * 6e4);
  return date.toISOString().split(/[-:.TZ]/).reduce(function(template, item, i) {
    return template.split(specs[i]).join(item);
  }, template);
}
//var date = new Date('2015-02-01T01:23:45.678Z');
