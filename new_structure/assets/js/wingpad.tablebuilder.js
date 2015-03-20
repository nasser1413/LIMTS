function TableBuilder(attributes) {
  this.finalized = false;
  this.numRows = 0;
  this.table = "";
  this.startElement('table', attributes);
}

TableBuilder.prototype.addData = function(innerHTML, attributes) {
  this.table += "\<td";
  this.buildAttributes(attributes);
  this.table += innerHTML + "\<\/td\>";
}

TableBuilder.prototype.addRow = function(attributes) {
  this.endRow();
  this.table += "\<tr";
  this.buildAttributes(attributes);
  this.numRows++;
}

TableBuilder.prototype.startElement = function(element, attributes) {
  this.numRows = 0;
  this.table += "\<" + element;
  this.buildAttributes(attributes);
}

TableBuilder.prototype.endElement = function(element) {
  this.endRow();
  this.table += "\<\/" + element + "\>";
}

TableBuilder.prototype.endRow = function() {
  if (this.numRows > 0 && (this.table.match(/\<\/[^\<\>]*\>$/) == null || this.table.match(/\<\/td\>$/) != null)) {
    this.table += "\<\/tr\>";
  }
}

TableBuilder.prototype.buildAttributes = function(attributes) {
  if (attributes != null) {
    for (attribute in attributes) {
      this.table += " " + attribute + "=\"" + attributes[attribute] + "\"";
    }
  }
  this.table += "\>";
}

TableBuilder.prototype.finalize = function() {
  if (!this.finalized) {
    this.endElement('table');
    this.finalized = true;
  }
}

TableBuilder.prototype.getHTML = function() {
  return this.table;
}
