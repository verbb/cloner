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
        this.$table = $('table#' + settings.id);
        this.title = 'New ' + settings.title + ' Name';
        this.action = 'cloner/' + settings.action;

        this.setupTable();
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
                var $actionElement = $(this).find('td.thin:first');
                var $cloneButton = $('<a class="add icon"></a>');

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

        var $row = $(e.currentTarget).parents('tr');
        var rowId = $row.data('id');
        var name;

        if (name = prompt(this.title)) {
            var data = {
                id: rowId,
                name: name,
                handle: this.generateHandle(name),
            };

            Craft.postActionRequest(this.action, data, $.proxy(function(response, textStatus) {
                window.location.reload();
            }, this));
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
