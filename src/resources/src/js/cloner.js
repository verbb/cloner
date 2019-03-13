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
            };

            Craft.postActionRequest(this.action, data, function(response) {
                if (response.success) {
                    window.location.reload();
                }
            });
        }
    },

});

})(jQuery);
