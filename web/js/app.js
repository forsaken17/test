var app = (function ($) {
    var m = {inProgress: false};
    var tArchive = tDone = 2, tInbox = tQueued = 1, tTrash = tOverdue = 3;
    m.init = function () {
        doneHandler();
        archiveHandler();
        editRowHandler();
        deleteHandler();
    };
    function editRowHandler() {
        var button = $('button[name="edit"]');
        button.on('click', function () {
            var $this = $(this), row = $this.closest('.task-row'), rowId = row.find('.id').text();
            var data = {};
            row.find('td.field').each(function () {
                var classList = $(this).attr('class').split(/\s+/);
                for (var i = 0; classList.length > i; i++) {
                    var className = classList[i];
                    if (className != 'field' && className != 'hidden') {
                        data[className] = $(this).text();
                        $('#editForm [name="' + className + '"]').val(data[className]);
                    }
                }

            });
        });
    }
    function stopEvent(e) {
        if (m.inProgress === true) {
            e.stopPropagation();
            return true;
        }
        m.inProgress = true;
    }
    function doneHandler() {
        var button = $('.done-tick');
        button.on('click', function (e) {
            var $this = $(this), row = $this.closest('.task-row'), rowId = row.find('.id').text();
            stopEvent(e);
            postHandler('/changeState', {'rowId': rowId, 'rowState': tDone}, function () {
                row.addClass('done-row');
                $this.prop('disabled', true);
                row.find('.state').text(tDone);
                row.find('button[name="edit"]').prop('disabled', true);
                m.inProgress = false;
            })

        });
    }
    function deleteHandler() {
        var button = $('button[name="delete"]');
        button.on('click', function (e) {
            var $this = $(this), row = $this.closest('.task-row'), rowId = row.find('.id').text();
            stopEvent(e);
            postHandler('/delete', {'rowId': rowId}, function () {
                row.addClass('archive-row').toggle();
                $this.prop('disabled', true);
                m.inProgress = false;
            })

        });
    }
    function archiveHandler() {
        var button = $('button[name="archive"]');
        button.on('click', function (e) {
            var $this = $(this), row = $this.closest('.task-row'), rowId = row.find('.id').text();
            stopEvent(e);
            postHandler('/changeCategory', {'rowId': rowId, 'rowCategory': tArchive}, function () {
                row.addClass('archive-row').toggle();
                $this.prop('disabled', true);
                m.inProgress = false;
            })

        });
    }
    function postHandler(url, data, callback) {
        $.post(url, data, function (data) {
            if (data.result == 'success') {
                callback();
            }

        }, "json");
    }
    return m;
}(jQuery));
