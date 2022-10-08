// ==========================================================================

// Cloner Plugin for Craft CMS
// Author: Verbb - https://verbb.io/

// ==========================================================================

if (typeof Craft.Cloner === typeof undefined) {
    Craft.Cloner = {};
}

(function($) {

Craft.Cloner = Garnish.Base.extend({
    $table: null,
    title: null,
    action: null,

    init: function(settings) {
        Garnish.requestAnimationFrame($.proxy(function() {
            this.$table = $('#' + settings.id + '-vue-admin-table');
            this.title = 'New ' + settings.title + ' Name';
            this.action = 'cloner/' + settings.action;

            this.setupTable();
        }, this));
    },

    setupTable: function() {
        var self = this;

        if (this.$table.length) {
            this.$table.find('thead tr').each(function() {
                var $actionElement = $(this).find('td.thin:first');

                if (!$actionElement.length) {
                    $(this).append('<td class="thin"></td>');
                } else {
                    var $col = $('<td class="thin"></td>');
                    $actionElement.before($col);
                }
            });

            this.$table.find('tbody tr').each(function() {
                var $actionElement = $(this).find('td a[role="button"]').parents('td');
                var $cloneButton = $('<a class="add icon"></a><span class="spinner cloner-spinner hidden"></span>');

                if (!$actionElement.length) {
                    var $col = $('<td class="thin"></td>').html($cloneButton);
                    $(this).append($col);
                } else {
                    var $col = $('<td class="thin"></td>').html($cloneButton);
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
