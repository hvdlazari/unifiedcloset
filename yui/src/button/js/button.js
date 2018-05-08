// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    atto_unifiedcloset
 * @copyright  2018 Hellen Cunha <hcunha@plus-it.com.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @module moodle-atto-unifiedcloset-button
 */

/**
 * Atto text editor unifiedcloset plugin.
 *
 * @namespace M.atto_unifiedcloset
 * @class button
 * @extends M.editor_atto.EditorPlugin
 */

var COMPONENTNAME = 'atto_unifiedcloset',
    CSS = {
        MASTER: 'block_unifiedcloset_master',
        GRID: 'block_unifiedcloset_grid',
        GRID_TABLE: 'block_unifiedcloset_grid_title',
        GRID_DIV_TABLE: 'block_unifiedcloset_grid_div_table',
        DIV_TABLE: 'table-responsive',
        TABLE: 'table'
    },
    TEMPLATES = {
        MASTER: ''+
        '<form class="atto_form">' +
            '<div class="{{CSS.MASTER}}">'+
                '<div class="{{CSS.GRID}}">'+
                    '<div class="{{CSS.GRID_TABLE}}">'+
                        '<a href="">'+
                            '<h3>{{get_string "h3" component}}</h3>'+
                        '</a>'+
                    '</div>'+
                    '<div class="{{CSS.GRID_DIV_TABLE}}">'+
                        '<div class="{{CSS.DIV_TABLE}}">'+
                            '<table id="block_unifiedcloset_grid_table" class="{{CSS.TABLE}}">'+
                                '<thead>'+
                                    '<th>{{get_string "th:thumbnail" component}}</th>'+
                                    '<th>{{get_string "th:title" component}}</th>'+
                                    '<th>{{get_string "th:file_link" component}}</th>'+
                                    '<th>{{get_string "th:type" component}}</th>'+
                                    '<th>{{get_string "th:date_created" component}}</th>'+
                                    '<th>{{get_string "th:edit_reg" component}}</th>'+
                                    '<th>{{get_string "th:exclude_logical" component}}</th>'+
                                    '<th>{{get_string "th:download" component}}</th>'+
                                    '<th>{{get_string "th:licence" component}}</th>'+
                                '</thead>'+
                            '</table>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</form>'
    };

Y.namespace('M.atto_unifiedcloset').Button = Y.Base.create('button', Y.M.editor_atto.EditorPlugin, [], {

    /**
     * A reference to the current selection at the time that the dialogue
     * was opened.
     *
     * @property _currentSelection
     * @type Range
     * @private
     */
    _currentSelection: null,

    /**
     * A reference to the dialogue content.
     *
     * @property _content
     * @type Node
     * @private
     */
    _content: null,

    initializer: function() {
        this.addButton({
            iconurl: M.cfg.wwwroot + '/lib/editor/atto/plugins/unifiedcloset/pix/icon.svg',
            callback: this._displayDialogue
        });
    },

    /**
     * Display the manage files.
     *
     * @method _displayDialogue
     * @private
     */
    _displayDialogue: function(e) {
        if (this.get('host').getSelection() === false) {
            return;
        }

        if (!('renderPartial' in Y.Handlebars.helpers)) {
            (function smashPartials(chain, obj) {
                Y.each(obj, function(value, index) {
                    chain.push(index);
                    if (typeof value !== "object") {
                        Y.Handlebars.registerPartial(chain.join('.').toLowerCase(), value);
                    } else {
                        smashPartials(chain, value);
                    }
                    chain.pop();
                });
            })([], TEMPLATES);

            Y.Handlebars.registerHelper('renderPartial', function(partialName, options) {
                if (!partialName) {
                    return '';
                }

                var partial = Y.Handlebars.partials[partialName];
                var parentContext = options.hash.context ? Y.clone(options.hash.context) : {};
                var context = Y.merge(parentContext, options.hash);
                delete context.context;

                if (!partial) {
                    return '';
                }
                return new Y.Handlebars.SafeString(Y.Handlebars.compile(partial)(context));
            });
        }

        var dialogue = this.getDialogue({
            headerContent: M.util.get_string('pluginname', COMPONENTNAME),
            width: '1150px',
            focusAfterHide: true
        });
        dialogue.set('bodyContent', this._getDialogueContent()).show();
    },

    /**
     * Return the dialogue content for the tool, attaching any required
     * events.
     *
     * @method _getDialogueContent
     * @return {Node} The content to place in the dialogue.
     * @private
     */
    _getDialogueContent: function() {
        this._content = Y.Node.create(
            Y.Handlebars.compile(TEMPLATES.MASTER)(this._getContext())
        );
        return this._content;
    },

    /**
     * Gets the root context for all templates, with extra supplied context.
     *
     * @method _getContext
     * @param  {Object} extra The extra context to add
     * @return {Object}
     * @private
     */
    _getContext: function(extra) {
        return Y.merge({
            elementid: this.get('host').get('elementid'),
            username: this.get('username'),
            dirid: this.get('dirid'),
            component: COMPONENTNAME,
            CSS: CSS
        }, extra);
    },

    /**
     * Returns the URL to the file manager.
     *
     * @param _getIframeURL
     * @return {String} URL
     * @private
     */
    _getIframeURL: function() {
        return M.cfg.wwwroot + '/lib/editor/atto/plugins/unifiedcloset/unifiedcloset.php?dirid='+this.get('dirid')+'&username='+this.get('username');
    }

}, {
    ATTRS: {
        dirid: {
            value: 0
        },
        username: {
            value: null
        }
    }
});
