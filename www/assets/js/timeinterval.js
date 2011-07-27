function TimeInterval(totalMs) {
  this.setTotalMilliseconds(totalMs);
}
TimeInterval.units = ['ms', 's', 'm', 'h', 'd'];
TimeInterval.factors = {ms: 1, s: 1000, m: 1000 * 60, h: 1000 * 60 * 60, d: 1000 * 60 * 60 * 24};
TimeInterval.prototype.convert = function(value, from, to) {
  return value * TimeInterval.factors[from] / TimeInterval.factors[to];
}
TimeInterval.prototype.setTotalMilliseconds = function(ms) {
  this._totalMilliseconds = ms;
}

TimeInterval.prototype.isNegative = function() {
  return this._totalMilliseconds < 0;
}
TimeInterval.prototype.total = function(unit) {
  return this.convert( Math.abs(this._totalMilliseconds), 'ms', unit);
}
TimeInterval.prototype.get = function(unit) {
  var value = Math.floor(this.total(unit));
  for (var k in TimeInterval.factors) {
    if (TimeInterval.factors[k] > TimeInterval.factors[unit]) {
      value = value - this.convert(this.get(k), k, unit);
    }
  }
  return value;
}
TimeInterval.prototype.round = function(unit) {
  var totalMs = this.convert( Math.round(this.total(unit)), unit, 'ms' );
  return new TimeInterval(totalMs);
}
TimeInterval.prototype.roundUp = function(unit) {
  var totalMs = this.convert( Math.ceil(this.total(unit)), unit, 'ms' );
  return new TimeInterval(totalMs);
}
TimeInterval.prototype.toString = function() {
  var str = [], val = null, units = TimeInterval.units;
  for (var k in units) {
    val = this.get( units[k] );
    if (val > 0)
      str.unshift( val + ' ' + units[k] );
  }
  return str.join(', ');
}
TimeInterval.prototype.format = function() {
  var str = this.toString().replace('h', 'hr').replace('m', 'min').replace(/,/g, '');
  if (str == '')
    return '0 s';
  else
    return str;
}
Date.prototype.timeUntil = function(otherDate) {
  return new TimeInterval(otherDate - this);
}
