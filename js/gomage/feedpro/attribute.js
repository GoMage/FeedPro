/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 3.7.0
 * @since        Class available since Release 4.0.0
 */

if (typeof GoMage == 'undefined') GoMage = {};

GoMage.Rows = function (config) {
    'use strict';
    this.config = config;
    this.container = $('rows-container');
    this.template = new Template($('row-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.count = 0;
    this.index = 0;
    this.conditions = [];
    this.values = [];
    this.init();
};

GoMage.Rows.prototype = {
    init: function () {
        this.config.rowsData.forEach(function (data) {
            this.add(data);
        }, this);
        if (this.config.rowsData.length == 0) {
            this.add({});
        }
        this.bindActions();
    },
    add: function (data) {
        var element;
        data.row_id = this.index;
        element = this.template.evaluate(data);
        Element.insert(this.container, element);

        this.conditions[this.index] = new GoMage.Conditions({
            url: this.config.url,
            row_id: this.index,
            conditionsData: (typeof data.conditions == 'undefined') ? [] : data.conditions
        });

        var _data = {
            row_id: this.index,
            type: (typeof data.type == 'undefined') ? 'static' : data.type
        };
        if (typeof data.value != 'undefined') {
            _data.value = data.value;
        }
        this.values[this.index] = new GoMage.Value(_data);

        this.count++;
        this.index++;
    },
    remove: function (event) {
        if (this.count == 1) {
            return;
        }
        var element = $(Event.findElement(event, 'tr'));
        if (element) {
            Element.remove(element);
            this.count--;
        }
    },
    changeType: function (event) {
        var element = $(Event.findElement(event, 'select'));
        if (element) {
            var row_id = element.readAttribute('data-row-id');
            this.values[row_id] = new GoMage.Value({
                row_id: row_id,
                type: element.getValue()
            });
        }
    },
    addCondition: function (event) {
        var element = $(Event.findElement(event, 'button'));
        if (element) {
            var row_id = element.readAttribute('data-row-id');
            this.conditions[row_id].add({
                    row_id: row_id
                }
            );
        }
    },
    addValue: function (event) {
        var element = $(Event.findElement(event, 'button'));
        if (element) {
            var row_id = element.readAttribute('data-row-id');
            if ((typeof this.values[row_id] != 'undefined')) {
                if (typeof this.values[row_id].object != 'undefined') {
                    this.values[row_id].object.add({
                            row_id: row_id
                        }
                    );
                }
            }
        }
    },
    bindActions: function () {
        Event.observe('add_new_row_button', 'click', this.add.bind(this, {}));
        this.container.on('click', '.delete-row', this.remove.bind(this));
        this.container.on('change', '.type-select', this.changeType.bind(this));
        this.container.on('click', '.add-condition', this.addCondition.bind(this));
        this.container.on('click', '.add-value', this.addValue.bind(this));
    }

};

GoMage.Conditions = function (config) {
    'use strict';
    this.config = config;
    this.container = $('conditions-container-' + config.row_id);
    this.template = new Template($('condition-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.count = 0;
    this.index = 0;
    this.init();
};

GoMage.Conditions.prototype = {
    init: function () {
        this.config.conditionsData.forEach(function (data) {
            data.row_id = this.config.row_id;
            this.add(data);
        }, this);
        if (this.config.conditionsData.length == 0) {
            this.add({
                row_id: this.config.row_id
            });
        }
        this.bindActions();
    },
    add: function (data) {
        var element;
        data.condition_id = this.index;
        element = this.template.evaluate(data);
        Element.insert(this.container, element);
        this.reloadAttributes();
        this.count++;
        this.index++;
    },
    remove: function (event) {
        if (this.count == 1) {
            return;
        }
        var element = $(Event.findElement(event, 'tr'));
        if (element) {
            Element.remove(element);
            this.count--;
        }
    },
    reloadAttributes: function () {
        this.container.select('.attribute-select').forEach(function (attribute) {
            if (!attribute.readAttribute('data-loaded')) {
                attribute.writeAttribute('data-loaded', true);
                this.reloadAttributeValues(attribute);
            }
        }, this);
    },
    changeAttribute: function (event) {
        var element = $(Event.findElement(event, 'select'));
        this.reloadAttributeValues(element);
    },
    reloadAttributeValues: function (attribute) {
        var elementId = attribute.readAttribute('data-value');

        var input = $(elementId + '_input'),
            select = $(elementId + '_select');

        if (attribute.value) {
            new Ajax.Request(this.config.url, {
                method: 'GET',
                parameters: {
                    'code': attribute.value
                },
                onSuccess: function (transport) {
                    eval('var response = ' + transport.responseText);
                    if (response.error) {
                        alert(response.message);
                    } else {
                        if (response.values.length) {
                            input.disable().hide();
                            select.enable().show();
                            select.options.length = 0;
                            response.values.forEach(function (data) {
                                select.insert(new Element('option', {value: data.value}).update(data.label));
                            });
                            if (input.value) {
                                select.value = input.value;
                                input.value = '';
                            }
                        } else {
                            input.enable().show();
                            select.disable().hide();
                        }
                    }
                }
            });
        } else {
            input.enable().show();
            select.disable().hide();
        }
    },
    bindActions: function () {
        this.container.on('click', '.delete-condition', this.remove.bind(this));
        this.container.on('change', '.attribute-select', this.changeAttribute.bind(this));
    }
};

GoMage.Value = function (config) {
    this.config = config;
    this.container = $('values-container-' + config.row_id);
    this.init();
};

GoMage.Value.prototype = {
    init: function () {
        this.container.innerHTML = '';
        var addButton = $$('button.add-value[data-row-id="' + this.config.row_id + '"]')[0];
        switch (this.config.type) {
            case 'attribute_set':
                var value = (typeof this.config.value == 'undefined') ? [] : this.config.value;
                this.object = new GoMage.Value_Attribute(this.container, this.config.row_id, value);
                addButton.show();
                break;
            case 'percent':
                var value = (typeof this.config.value == 'undefined') ? {} : this.config.value;
                this.object = new GoMage.Value_Percent(this.container, this.config.row_id, value);
                addButton.hide();
                break;
            case 'conf_values':
                var value = (typeof this.config.value == 'undefined') ? {} : this.config.value;
                this.object = new GoMage.Value_Configurable(this.container, this.config.row_id, value);
                addButton.hide();
                break;
            case 'static':
            default:
                var value = (typeof this.config.value == 'undefined') ? '' : this.config.value;
                this.object = new GoMage.Value_Static(this.container, this.config.row_id, value);
                addButton.hide();
                break;
        }
    }
};

GoMage.Value_Attribute = function (container, row_id, value) {
    this.template = new Template($('attribute-value-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.container = container;
    this.row_id = row_id;
    this.value = value;
    this.count = 0;
    this.index = 0;
    this.init();
};

GoMage.Value_Attribute.prototype = {
    init: function () {
        this.value.forEach(function (data) {
            data.row_id = this.row_id;
            this.add(data);
        }, this);
        if (this.value.length == 0) {
            this.add({
                row_id: this.row_id
            });
        }
        this.bindActions();
        return this;
    },
    add: function (data) {
        var element;
        data.value_id = this.index;
        element = this.template.evaluate(data);
        Element.insert(this.container, element);
        this.count++;
        this.index++;
    },
    remove: function (event) {
        if (this.count == 1) {
            return;
        }
        var element = $(Event.findElement(event, 'tr'));
        if (element) {
            Element.remove(element);
            this.count--;
        }
    },
    bindActions: function () {
        this.container.on('click', '.delete-value', this.remove.bind(this));
    }
};

GoMage.Value_Configurable = function (container, row_id, value) {
    this.template = new Template($('configurable-value-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.container = container;
    this.row_id = row_id;
    this.value = value;
    this.init();
};

GoMage.Value_Configurable.prototype = {
    init: function () {
        var element, data;
        data = {
            row_id: this.row_id,
            prefix: (typeof this.value.prefix == 'undefined') ? '' : value.prefix,
            code: (typeof this.value.code == 'undefined') ? '' : value.code
        };
        element = this.template.evaluate(data);
        Element.insert(this.container, element);
    }
};

GoMage.Value_Percent = function (container, row_id, value) {
    this.template = new Template($('percent-value-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.container = container;
    this.row_id = row_id;
    this.value = value;
    this.init();
};

GoMage.Value_Percent.prototype = {
    init: function () {
        var element, data;
        data = {
            row_id: this.row_id,
            percent: (typeof this.value.percent == 'undefined') ? '' : this.value.percent,
            code: (typeof this.value.code == 'undefined') ? '' : this.value.code
        };
        element = this.template.evaluate(data);
        Element.insert(this.container, element);
    }
};

GoMage.Value_Static = function (container, row_id, value) {
    this.template = new Template($('static-value-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.container = container;
    this.row_id = row_id;
    this.value = value;
    this.init();
};

GoMage.Value_Static.prototype = {
    init: function () {
        var element, data;
        data = {
            row_id: this.row_id,
            value: this.value
        };
        element = this.template.evaluate(data);
        Element.insert(this.container, element);
    }
};