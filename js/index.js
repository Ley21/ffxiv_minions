var XIVDBTooltips;
var valid = getUrlParameter("lang") === undefined || getUrlParameter("lang") == "en";
var xivdb_tooltip_base_url = valid ? 'https://xivdb.com' : 'https://' + getUrlParameter("lang") + ".xivdb.com";
var xivdb_tooltips = {
    // the XIVDB server to query (this will be https soon)
    xivdb: xivdb_tooltip_base_url,

    // the language the tooltips should be
    language: getUrlParameter("lang"),

    seturlname: false,

    // tooltips require JQuery and "check" it, this can add a half a second delay
    // if you have jquery already on your site, set this false
    jqueryEmbed: false,

    // whether to include the content icon next to the link
    seturlicon: false
};
$(document).on("submit", "form", function(e) {
    e.preventDefault();
    var formSubmit = $('#char_search').serialize();

    searchCharakter(getLangData() + "&" + formSubmit);

    // document.getElementById("content").innerHTML = $.get( "charakter.php?"+formSubmit );

    return false;
});

$(document).on("click", '.ranking_dropdown', function() {
    loadRanking(getLangData() + "&type=" + this.id);
});
$(document).on("click", '.freeCompany', function() {
    loadFreeCompany(getLangData() + "&fc=" + this.id);
});

$(document).on("click", '.minions_methode', function() {
    loadMinions(getLangData() + "&methode=" + this.id);
});

$(document).on("click", '.mounts_methode', function() {
    loadMounts(getLangData() + "&methode=" + this.id);
});
$(document).on("click", '#char_button', function() {
    browserAs();
});

$(document).on("click", '#user', function() {
    var p_id = getCookie("player_id");
    loadCharakter(p_id);
});
$(document).on("click", '#faq', function() {
    loadFaq(getLangData());
});

$(document).ready(function() {
    browserView()
    var pathArray = window.location.pathname.split('/');
    var data = decodeURIComponent(window.location.search.substring(1));
    var last = pathArray[pathArray.length - 1];
    if (last == "char") {
        var id = getUrlParameter("id");
        if (id) {
            loadCharakter(id);
        } else {
            searchCharakter(data);
        }


    } else if (last == "ranking") {
        loadRanking(data);
    } else if (last == "minions") {
        loadMinions(data);
    } else if (last == "mounts") {
        loadMounts(data);
    } else if (last == "freeCompany") {
        loadFreeCompany(data);
    } else if (last == "faq") {
        loadFaq(data);
    } else {
        pushUrl("", getLangData());
        $('.table').DataTable();
    }

});
$(document).on('draw.dt', '.table', function() {

    XIVDBTooltips.get();
    browserView();
});

$(document).on("change", '#lang', function() {

    // window.location.search = jQuery.query.set("lang", this.value);

    var url = window.location.href;
    var newUrl = url.replace(getLangData(), "lang=" + this.value)
    window.history.pushState("object or string", "", newUrl);
    location.reload();
});

$(document).on("change", '#find_minion', function() {
    $("tr").removeClass("success");
    $.ajax({
        url: "handler/find_player_minions.php",
        data: "minion=" + this.value,
        type: 'get',
        success: function(data) {
            var obj = JSON.parse(data);
            obj.forEach(function(id) {
                $("#" + id).addClass("success");
            });
        }
    });
});
$(document).on('show.bs.modal', '#updateDB', function() {
    var modal = $(this);
    var body = modal.find('.modal-body');
    basicAjaxCall("update_database.php", "", function(data) {}, get_language_text("get_latest_ids"), body);

});

$(document).on('show.bs.modal', '#request', function() {
    var modal = $(this);
    var body = modal.find('.modal-body');
    basicAjaxCall("request_change.php", getLangData(), function(data) {}, "", body);
});

$(document).on('click', '#update_button', function() {
    var modal = $('#updateDB');
    var body = modal.find('.modal-body');
    var key = $('#key').val();
    var update = $('#update').is(':checked');

    if (update == true) {
        var minion_id = parseInt($('#minion_id').text());
        async_database_update(minion_id, "minion", "update_minions");

        var mount_id = parseInt($('#mount_id').text());
        async_database_update(mount_id, "mount", "update_mounts");
    }
    var method_update = $('#method_update').is(':checked');
    var readonly = $('#readonly').is(':checked');
    if (method_update) {
        async_call(function() {
            basicAjaxCall("update_database.php", "key=" + key + "&update=true&method_update=" + method_update + "&readonly=" + readonly,
                function(data) {}, get_language_text("read_methodes"), body, "post");
        }, function() {});

    }



})

$(document).on("change", '#type', function() {
    update_request_modal(false);

});
$(document).on("change", '#obj_name', function() {
    update_request_modal(false);
});
$(document).on("change", '#method', function() {
    update_request_modal(false);
    $("#send_button").removeClass("disabled");
});
$(document).on("change", '#new_method', function() {
    update_request_modal(false);
    $("#send_button").removeClass("disabled");
});
$(document).on('click', '#send_button', function() {
    update_request_modal(true);
    $(this).addClass("disabled");
});

function update_request_modal(send) {
    var modal = $('#request');
    var body = modal.find('.modal-body');

    var type = $('#type').val() === undefined ? "" : $('#type').val();
    var obj_name = $('#obj_name').val() === undefined || $('#obj_name').val() == null ? "" : $('#obj_name').val();
    var method = $('#method').val() === undefined ? $('#new_method').val() : $('#method').val();
    method = method === undefined ? "" : method;
    var method_description_en = $('#method_description_en').val();
    var method_description_fr = $('#method_description_fr').val();
    var method_description_de = $('#method_description_de').val();
    var method_description_ja = $('#method_description_ja').val();
    var submit = getLangData() + "&send=" + send + "&type=" + type + "&obj_name=" + obj_name + "&method=" + method +
        "&method_description_en=" + method_description_en + "&method_description_fr=" + method_description_fr +
        "&method_description_de=" + method_description_de + "&method_description_ja=" + method_description_ja;
    basicAjaxCall("request_change.php", submit, function(data) {}, "", body, "post");
}