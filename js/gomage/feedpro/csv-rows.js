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

GoMage.CsvRows = function (config) {
    'use strict';
    this.config = config;
    this.container = $('rows-container');
    this.template = new Template($('row-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.titleTemplate = new Template($('title-template').innerHTML, /(^|.|\r|\n)({{(\w+)}})/);
    this.index = 0;
    this.init();
};

GoMage.CsvRows.prototype = {
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

        var template = document.createElement('div');
        template.innerHTML = element;

        if (typeof data.type != 'undefined') {
            $(template).down('select.type-select').down('option[value=' + data.type + ']')
                .writeAttribute('selected', 'selected');
        }
        if (typeof data.attribute_value != 'undefined') {
            $(template).down('select.attribute-value-select').down('option[value=' + data.attribute_value + ']')
                .writeAttribute('selected', 'selected');
        }
        if (typeof data.output_type != 'undefined') {
            data.output_type.split(',').forEach(function (v) {
                $(template).down('select.output-type-select').down('option[value=' + v + ']')
                    .writeAttribute('selected', 'selected');
            }, this);
        }
        if (typeof data.prefix_type != 'undefined') {
            $(template).down('select.prefix-type-select').down('option[value=' + data.prefix_type + ']')
                .writeAttribute('selected', 'selected');
        }
        if (typeof data.attribute_prefix_value != 'undefined') {
            $(template).down('select.prefix-value-select').down('option[value=' + data.attribute_prefix_value + ']')
                .writeAttribute('selected', 'selected');
        }
        if (typeof data.suffix_type != 'undefined') {
            $(template).down('select.suffix-type-select').down('option[value=' + data.suffix_type + ']')
                .writeAttribute('selected', 'selected');
        }
        if (typeof data.attribute_suffix_value != 'undefined') {
            $(template).down('select.suffix-value-select').down('option[value=' + data.attribute_suffix_value + ']')
                .writeAttribute('selected', 'selected');
        }

        element = $(template).innerHTML;
        Element.insert(this.container, element);
        this.setValues();
        this.setTitle(data.row_id);
        this.index++;
    },
    remove: function (event) {
        var element = $(Event.findElement(event, 'div.fm-block'));
        if (element) {
            Element.remove(element);
        }
    },
    toggleEdit: function (event) {
        var element = $(Event.findElement(event, 'div.fm-block'));
        if (element) {
            var row_id = element.readAttribute('data-row-id');
            this.setTitle(row_id);
            element.toggleClassName('__opened');
        }
    },
    changeType: function (event) {
        var element = $(Event.findElement(event, 'select'));
        if (element) {
            this.setValue(element);
        }
    },
    setValues: function () {
        this.container.select('.type-select, .prefix-type-select, .suffix-type-select').forEach(function (typeField) {
            if (!typeField.readAttribute('data-loaded')) {
                typeField.writeAttribute('data-loaded', true);
                this.setValue(typeField);
            }
        }, this);
    },
    setValue: function (typeField) {
        var elementId = typeField.readAttribute('data-value');
        var input = $(elementId + '_input'),
            select = $(elementId + '_select');

        if (typeField.value == 'static') {
            input.enable().show();
            select.disable().hide();
        } else {
            input.disable().hide();
            select.enable().show();
        }
    },
    setTitle: function (row_id) {
        var data = {},
            element,
            container = $('title-container-' + row_id);

        data.name = this.getTitleValue(row_id, 'name');
        if (!data.name) {
            data.name = 'New Row';
        }

        data.type = this.getTitleValue(row_id, 'type');
        data.value = this.getTitleValue(row_id, 'value');

        data.prefix_type = this.getTitleValue(row_id, 'prefix_type');
        data.prefix_value = this.getTitleValue(row_id, 'prefix_value');
        data.prefix_display = data.prefix_type && data.prefix_value ? 'inline-block' : 'none';

        data.suffix_type = this.getTitleValue(row_id, 'suffix_type');
        data.suffix_value = this.getTitleValue(row_id, 'suffix_value');
        data.suffix_display = data.suffix_type && data.suffix_value ? 'inline-block' : 'none';

        element = this.titleTemplate.evaluate(data);

        container.innerHTML = '';
        Element.insert(container, element);
    },

    getTitleValue: function (row_id, name) {
        var input = $$('input[data-title=' + this.config.htmlName + '_' + row_id + '_' + name + ']'),
            select = $$('select[data-title=' + this.config.htmlName + '_' + row_id + '_' + name + ']');
        if (!select.length || select[0].disabled) {
            return input[0].value;
        }
        if (select[0].value) {
            select = select[0];
            return select[select.selectedIndex].text;
        }
        return '';
    },

    bindActions: function () {
        Event.observe('add_new_row_button', 'click', this.add.bind(this, {}));
        this.container.on('click', '.delete-row', this.remove.bind(this));
        this.container.on('click', '.edit-row, .close-row', this.toggleEdit.bind(this));
        this.container.on('change', '.type-select, .prefix-type-select, .suffix-type-select', this.changeType.bind(this));
    }
};

