/* Filters API for LIMTS
 *  Provides a better way to manipulate the "persisting" filters in LIMTS
 *
 *  Written By: Justin Szaday (justin.szaday@valpo.edu)
 *  Requirements: jquery & jquery-deparam
 *  Last Updated: 03/31/2015 1:03AM
 */

function _Filters() {
  // Pseudo-Getter/Setter for Accessing the Page's Hash
  Object.defineProperty(this, "filters", {
    get: function() {
      // Deparameterize the hash string
      return $.deparam(location.hash.substr(1));
    },
    set: function(val) {
      // Parameterize the provide object and set it as the hash
      location.hash = $.param(val);
    }
  });
}

// Easier syntax for setting the hashchange event handler
_Filters.prototype = {
  set hashchange(handler) {
    // bind the handler to receive future hash changes
    $(window).bind("hashchange", handler);
    // NOTE: You can optionally trigger it if initialization is needed
    // handler();
  }
}

// Accepts a single filter or an array of filters to add
_Filters.prototype.add = function(type, data) {
  /* If no data was provided then exit
   * Otherwise, if the data is not an array then encapsulate it as one
   */
  if (!data) {
    return;
  } else if (data.constructor !== Array) {
    data = [ data ];
  }

  // Grab the current filters
  var filters = this.filters;
  // For each of the provided ids
  $.each(data, function(i, id) {
    // Convert the id to a string (since hashes are picky!)
    id = id.toString();
    /* If the filter was previously undefined then encapsulate and add the id
     * Otherwise, if the id is not already in the array then add it
     */
    if (!filters[type]) {
      filters[type] = [ id ];
    } else if ($.inArray(id, filters[type]) === -1) {
      filters[type].push(id);
    }
  });
  // Then update our filters accordingly
  this.filters = filters;
};

// Accepts a single filter or an array of filters to remove
_Filters.prototype.remove = function(type, data) {
  /* If no data was provided then exit
   * Otherwise, if the data is not an array then encapsulate it as one
   */
  if (!data) {
    return;
  } else if (data.constructor !== Array) {
    data = [ data ];
  }

  // Grab the current filters
  var filters = this.filters;
  // For each of the provided ids
  $.each(data, function(i, id) {
    // Convert the id to a string (since hashes are picky!)
    id = id.toString();
    // If the id is found in the array then remove it
    var index = $.inArray(id, filters[type]);
    if (index !== -1) {
      filters[type].splice(index, 1);
    }
  });
  // Then update our filters accordingly
  this.filters = filters;
};

// Removes all filters of a given type
_Filters.prototype.removeAll = function(type) {
  // Grab the current filters
  var filters = this.filters;
  // Nullify the filters corresponding to our type
  filters[type] = undefined;
  // Then update our filters accordingly
  this.filters = filters;
};

// Instantiate the singleton after all is said and done...
var Filters = new _Filters();
