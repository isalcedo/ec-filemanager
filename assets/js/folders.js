$(document).ready(function () {
    if ($('#folders-parent_folder').length && $('#folders-parent_folder').val().length > 0) {
        var values = $('#folders-parent_folder').val().split("/");
        var lastLi = values[values.length - 1];

        if ($('#folderstree').length) {
            values.forEach(function(li) {
                $('#'+li).addClass('jstree-open');
            });
            $('#'+lastLi).addClass('jstree-clicked');
        }
    }

    if ($('#folderstree').length) {
        $('#folderstree').jstree({
            "plugins": ["types"],
            "types": {
                "default": {
                    "icon": "glyphicon glyphicon-folder-close"
                },
                "main": {
                    "icon": "glyphicon glyphicon-folder-close"
                }
            },
            'core': {
                'multiple': false,
            }
        });
        $('#folderstree').on("changed.jstree", function (e, data) {
            console.log(data.node);
            if ($('#folders-parent_folder').length) {
                const parents = data.node.parents;
                if (parents.length > 1) {
                    parents.pop();
                    parents.reverse();
                    var text = '';
                    parents.forEach(function(dir) {
                        text = text + '/' + dir;
                    });
                    $('#folders-parent_folder').val(text + '/' + data.selected[0]);
                } else {
                    $('#folders-parent_folder').val(data.selected[0]);
                }
            }
        });
    }
});