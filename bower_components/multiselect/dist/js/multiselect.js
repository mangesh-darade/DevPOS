/*
 * @license
 *
 * Multiselect v2.5.1
 * http://crlcu.github.io/multiselect/
 *
 * Copyright (c) 2016-2018 Adrian Crisan
 * Licensed under the MIT license (https://github.com/crlcu/multiselect/blob/master/LICENSE)
 */

if (typeof jQuery === 'undefined') {
    throw new Error('multiselect requires jQuery');
}

;(function ($) {
    'use strict';

    var version = $.fn.jquery.split(' ')[0].split('.');

    if (version[0] < 2 && version[1] < 7) {
        throw new Error('multiselect requires jQuery version 1.7 or higher');
    }
})(jQuery);

;(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module depending on jQuery.
        define(['jquery'], factory);
    } else {
        // No AMD. Register plugin with global jQuery object.
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    var Multiselect = (function($) {
        /** Multiselect object constructor
         *
         *  @class Multiselect
         *  @constructor
        **/
        function Multiselect( $select, settings ) {
            var id = $select.prop('id');
            this.$left = $select;
            this.$right = $( settings.right ).length ? $( settings.right ) : $('#' + id + '_to');
            this.actions = {
                $leftAll:       $( settings.leftAll ).length ? $( settings.leftAll ) : $('#' + id + '_leftAll'),
                $rightAll:      $( settings.rightAll ).length ? $( settings.rightAll ) : $('#' + id + '_rightAll'),
                $leftSelected:  $( settings.leftSelected ).length ? $( settings.leftSelected ) : $('#' + id + '_leftSelected'),
                $rightSelected: $( settings.rightSelected ).length ? $( settings.rightSelected ) : $('#' + id + '_rightSelected'),

                $undo:          $( settings.undo ).length ? $( settings.undo ) : $('#' + id + '_undo'),
                $redo:          $( settings.redo ).length ? $( settings.redo ) : $('#' + id + '_redo'),

                $moveUp:        $( settings.moveUp ).length ? $( settings.moveUp ) : $('#' + id + '_move_up'),
                $moveDown:      $( settings.moveDown ).length ? $( settings.moveDown ) : $('#' + id + '_move_down')
            };

            delete settings.leftAll;
            delete settings.leftSelected;
            delete settings.right;
            delete settings.rightAll;
            delete settings.rightSelected;
            delete settings.undo;
            delete settings.redo;
            delete settings.moveUp;
            delete settings.moveDown;

            this.options = {
                keepRenderingSort:  settings.keepRenderingSort,
                submitAllLeft:      settings.submitAllLeft !== undefined ? settings.submitAllLeft : true,
                submitAllRight:     settings.submitAllRight !== undefined ? settings.submitAllRight : true,
                search:             settings.search,
                ignoreDisabled:     settings.ignoreDisabled !== undefined ? settings.ignoreDisabled : false,
                matchOptgroupBy:    settings.matchOptgroupBy !== undefined ? settings.matchOptgroupBy : 'label'
            };

            delete settings.keepRenderingSort, settings.submitAllLeft, settings.submitAllRight, settings.search, settings.ignoreDisabled, settings.matchOptgroupBy;

            this.callbacks = settings;

            if ( typeof this.callbacks.sort == 'function' ) {
                var sort = this.callbacks.sort;

                this.callbacks.sort = {
                    left: sort,
                    right: sort,
                };
            }

            this.init();
        }

        Multiselect.prototype = {
            init: function() {
                var self = this;
                self.undoStack = [];
                self.redoStack = [];

                if (self.options.keepRenderingSort) {
                    self.skipInitSort = true;

                    if (self.callbacks.sort !== false) {
                        self.callbacks.sort = {
                            left: function(a, b) {
                                return $(a).data('position') > $(b).data('position') ? 1 : -1;
                            },
                            right: function(a, b) {
                                return $(a).data('position') > $(b).data('position') ? 1 : -1;
                            },
                        };
                    }

                    self.$left.attachIndex();

                    self.$right.each(function(i, select) {
                        $(select).attachIndex();
                    });
                }

                if ( typeof self.callbacks.startUp == 'function' ) {
                    self.callbacks.startUp( self.$left, self.$right );
                }

                if ( !self.skipInitSort ) {
                    if ( typeof self.callbacks.sort.left == 'function' ) {
                        self.$left.mSort(self.callbacks.sort.left);
                    }

                    if ( typeof self.callbacks.sort.right == 'function' ) {
                        self.$right.each(function(i, select) {
                            $(select).mSort(self.callbacks.sort.right);
                        });
                    }
                }

                // Append left filter
                if (self.options.search && self.options.search.left) {
                    self.options.search.$left = $(self.options.search.left);
                    self.$left.before(self.options.search.$left);
                }

                // Append right filter
                if (self.options.search && self.options.search.right) {
                    self.options.search.$right = $(self.options.search.right);
                    self.$right.before($(self.options.search.$right));
                }

                // Initialize events
                self.events();
            },

            events: function() {
                var self = this;

                // Attach event to left filter
                if (self.options.search && self.options.search.$left) {
                    self.options.search.$left.on('keyup', function(e) {
                        if (self.callbacks.fireSearch(this.value)) {
                            var $toShow = self.$left.find('option:search("' + this.value + '")').mShow();
                            var $toHide = self.$left.find('option:not(:search("' + this.value + '"))').mHide();
                            var $grpHide = self.$left.find('option').closest('optgroup').mHide();
                            var $grpShow = self.$left.find('option:not(.hidden)').parent('optgroup').mShow();
                        } else {
                            self.$left.find('option, optgroup').mShow();
                        }
                    });
                }

                // Attach event to right filter
                if (self.options.search && self.options.search.$right) {
                    self.options.search.$right.on('keyup', function(e) {
                        if (self.callbacks.fireSearch(this.value)) {
                            var $toShow = self.$right.find('option:search("' + this.value + '")').mShow();
                            var $toHide = self.$right.find('option:not(:search("' + this.value + '"))').mHide();
                            var $grpHide = self.$right.find('option').closest('optgroup').mHide();
                            var $grpShow = self.$right.find('option:not(.hidden)').parent('optgroup').mShow();
                        } else {
                            self.$right.find('option, optgroup').mShow();
                        }
                    });
                }

                // Select all the options from left and right side when submiting the parent form
                self.$right.closest('form').on('submit', function(e) {
                    if (self.options.search) {
                        // Clear left search input
                    }
                });