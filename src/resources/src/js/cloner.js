// ==========================================================================

// Cloner Plugin for Craft CMS
// Author: Verbb - https://verbb.io/

// ==========================================================================

if (typeof Craft.Cloner === typeof undefined) {
    Craft.Cloner = {};
}

(function($) {

Craft.Cloner = Garnish.Base.extend({
    table: null,
    $table: null,
    title: null,
    action: null,

    init: function(settings) {
        Garnish.requestAnimationFrame($.proxy(function() {
            this.$table = $('#' + settings.id + '-vue-admin-table');
            this.title = 'New ' + settings.title + ' Name';
            this.action = 'cloner/' + settings.action;

            // Not everything is using Vuetable
            if (!this.$table.length) {
                this.$table = $('#' + settings.id);
            }

            // Setup the table for on-load instances
            this.setupTable();

            this.table = document.querySelector('#' + settings.id + '-vue-admin-table table');

            // Some admin tables are lazy-loaded and filterable (Entry Types) and because they're Vue-based
            // we don't have access to events. So watch for row changes to the table to re-bind things.
            this.observeTableRows(this.table, function() {
                this.setupTable();
            }.bind(this));
        });
    },

    observeTableRows: function(tableElement, callback) {
        // Check if the input is a valid table element
        if (!(tableElement instanceof HTMLTableElement)) {
            throw new Error("The provided element is not a valid HTMLTableElement.");
        }

        // Callback function for the MutationObserver
        function mutationCallback(mutationsList) {
            for (const mutation of mutationsList) {
                // Check if the mutation affects the table rows
                if (mutation.type === 'childList' && (mutation.target.tagName === 'TBODY' || mutation.target.tagName === 'TABLE')) {
                    callback();
                    break;
                }
            }
        }

        const observer = new MutationObserver(mutationCallback);

        // Start observing the table element
        observer.observe(tableElement, {
            childList: true,
            subtree: true // To capture changes in tbody and rows
        });
    },

    setupTable: function() {
        var self = this;

        if (this.$table.length) {
            // Cleanup in case of re-binding due to mutation observer
            this.$table.find('.cloner-th').remove();
            this.$table.find('.cloner-td').remove();

            this.$table.find('thead tr').each(function() {
                var $actionElement = $(this).find('td.thin:first');

                if (!$actionElement.length) {
                    $(this).append('<td class="thin cloner-th"></td>');
                } else {
                    var $col = $('<td class="thin cloner-th"></td>');
                    $actionElement.before($col);
                }
            });

            this.$table.find('tbody tr').each(function() {
                var $actionElement = $(this).find('td a[role="button"]').parents('td');
                var $cloneButton = $('<a href="#" class="add icon"></a><span class="spinner cloner-spinner hidden"></span>');

                if (!$actionElement.length) {
                    var $col = $('<td class="thin cloner-td"></td>').html($cloneButton);
                    $(this).append($col);
                } else {
                    var $col = $('<td class="thin cloner-td"></td>').html($cloneButton);
                    $actionElement.before($col);
                }

                self.addListener($cloneButton, 'click', 'onCloneButtonClick');
            });
        }
    },

    onCloneButtonClick: function(e) {
        e.preventDefault();

        // Vuetable doesn't give us the IDs of each row, so figure it out
        var $row = $(e.currentTarget).parents('tr');
        var href = $row.find('td:first a').attr('href');

        // Some tables use `th`
        if (!href) {
            href = $row.find('th:first a').attr('href');
        }

        var segments = href.split('?')[0].split('/');
        var rowId = segments[segments.length - 1];
        var name;

        var $addBtn = $(e.currentTarget);
        var $spinner = $(e.currentTarget).siblings('.spinner');

        if (name = prompt(this.title)) {
            var data = {
                id: rowId,
                name: name,
                handle: this.generateHandle(name),
            };

            $addBtn.addClass('hidden');
            $spinner.removeClass('hidden');

            Craft.sendActionRequest('POST', this.action, { data })
                .then((response) => {
                    window.location.reload();
                })
                .catch(({response}) => {
                    $addBtn.removeClass('hidden');
                    $spinner.addClass('hidden');

                    if (response && response.data && response.data.message) {
                        Craft.cp.displayError(response.data.message);
                    } else {
                        Craft.cp.displayError();
                    }
                });
        }
    },

    generateHandle: function(sourceVal) {
        // Remove HTML tags
        var handle = sourceVal.replace("/<(.*?)>/g", '');

        // Remove inner-word punctuation
        handle = handle.replace(/['"‘’“”\[\]\(\)\{\}:]/g, '');

        // Make it lowercase
        handle = handle.toLowerCase();

        // Convert extended ASCII characters to basic ASCII
        handle = Craft.asciiString(handle);

        // Handle must start with a letter
        handle = handle.replace(/^[^a-z]+/, '');

        // Get the "words"
        var words = Craft.filterArray(handle.split(/[^a-z0-9]+/)),
            handle = '';

        // Make it camelCase
        for (var i = 0; i < words.length; i++) {
            if (i == 0) {
                handle += words[i];
            } else {
                handle += words[i].charAt(0).toUpperCase()+words[i].substr(1);
            }
        }

        return handle;
    },

});

})(jQuery);
