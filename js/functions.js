var languageTexts = {
    'update_message': {
        en: "Character will be update from lodestone",
        fr: "",
        de: "Der Charakter wird vom Lodestone aktualisiert",
        ja: ""
    },
    'load_char_message': {
        en: "Loading Character",
        fr: "",
        de: "Charakter wird geladen",
        ja: ""
    },
    'load_ranking': {
        en: "Loading Ranking",
        fr: "",
        de: "Rangliste wird geladen",
        ja: ""
    },
    'load_freecompany': {
        en: "Loading Free Company Ranking",
        fr: "",
        de: "Freie Gesellschafts Rangliste wird geladen",
        ja: ""
    },
    'load_minions': {
        en: "Loading Minions",
        fr: "",
        de: "Begleiter werden geladen",
        ja: ""
    },
    'load_mounts': {
        en: "Loading Mounts",
        fr: "",
        de: "Reittiere werden geladen",
        ja: ""
    },
    'get_latest_ids': {
        en: "Getting last ids for collector objects...",
        fr: "",
        de: "Lade die letzen Ids für die Objekte...",
        ja: ""
    },
    'update_minions': {
        en: "Updating minions in database",
        fr: "",
        de: "Begleiter in der Datenbank werden aktuallisiert",
        ja: ""
    },
    'update_mounts': {
        en: "Updating mounts in database",
        fr: "",
        de: "Reittiere in der Datenbank werden aktuallisiert",
        ja: ""
    },
    'read_methodes': {
        en: "Read methodes from json file and update them in database.",
        fr: "",
        de: "Lese die Methoden aus der JSON Datei und aktuallisiere die Datenbank.",
        ja: ""
    },
    'request_change': {
        en: ".",
        fr: "",
        de: "Lese die Methoden aus der JSON Datei und aktuallisiere die Datenbank.",
        ja: ""
    },
};

function get_language_text(key) {
    var lang = getUrlParameter("lang");
    var text = languageTexts[key][lang];
    return text == "" ? languageTexts[key].en : text;
}

function pushUrl(type, urlData) {
    window.history.pushState("object or string", "", "/" + type + "?" + urlData);
}

function loadCharakter(id) {
    var submit = decodeURIComponent(window.location.search.substring(1));
    submit = updateSubmit(submit,"id",id);
    checkCharakter(submit, function(update) {
        ajaxCall("char", "charakter.php", submit, function(data) {},
            update ? get_language_text("update_message") : get_language_text("load_char_message"));
    });

}

function checkCharakter(submit, func) {
    ajaxCall("", "check_charakter.php", submit, function(data) {
        func(data.indexOf("true") > -1);
    }, get_language_text("load_char_message"));
}

function searchCharakter(formData) {
    checkCharakter(formData, function(update) {
        ajaxCall("char", "charakter.php", formData, function(data) {},
            update ? get_language_text("update_message") : get_language_text("load_char_message"));
    });
}

function updateCharakter(id) {
    loadingMessage(get_language_text("update_message"));
    $.ajax({
        url: "handler/charakter.php",
        data: "id=" + id + "&show=false",
        type: 'get',
        success: function(data) {
            var submit = decodeURIComponent(window.location.search.substring(1));
            if (submit.indexOf("fc=") > -1) {
                loadFreeCompany(submit);
            } else {
                loadRanking(submit);
            }
        }
    });

}

function getLangData() {
    var lang = getUrlParameter("lang");
    lang = lang ? lang : "en"
    return "lang=" + lang;
}

function loadRanking(submit) {

    //$('#content').html("<center><h2>Loading Ranking...</h2></center>");
    ajaxCall("ranking", "ranking.php", submit, function(data) {}, get_language_text("load_ranking"));
}

function loadFreeCompany(submit) {

    //$('#content').html("<center><h2>Loading Ranking...</h2></center>");
    ajaxCall("freeCompany", "fc_ranking.php", submit, function(data) {}, get_language_text("load_freecompany"));
}

function loadMinions(submit) {
    //$('#content').html("<center><h2>Loading Minions...</h2></center>");
    ajaxCall("minions", "get_minions.php", submit, function(data) {}, get_language_text("load_minions"));
}

function loadMounts(submit) {
    //$('#content').html("<center><h2>Loading Mounts...</h2></center>");
    ajaxCall("mounts", "get_mounts.php", submit, function(data) {}, get_language_text("load_mounts"));
}

function loadingMessage(customMessage = "", object = null) {
    object = object != null ? object : $("#content");
    object.html("<p><center><img src='img/gears.gif'><h2>" + customMessage + "</h2></center></p>");
}

function loadFaq(submit) {
    ajaxCall("faq", "faq.php", submit, function(data) {});
}

function basicAjaxCall(url, submitData, func, customMessage = "", object = null, type = 'get', async = true) {
    object = object != null ? object : $("#content");
    object.html("");
    if (customMessage != null) {
        loadingMessage(customMessage, object);
    }
    $.ajax({
        url: "handler/" + url,
        data: submitData,
        context: document.body,
        type: type,
        async: async,
        success: function(data) {
            object.html(data);
            func(data);
        }
    });
}

function ajaxCall(baseurl, url, submitData, func, customMessage = "") {

    basicAjaxCall(url, submitData, function(data) {
        func(data);
        submitData = showDataTable(baseurl,submitData);
        $('[data-toggle="tooltip"]').tooltip();
        var id = getCookie("player_id");
        set_char(id == "");
        browserView();
        pushUrl(baseurl, submitData);
    }, customMessage);
}

function showDataTable(baseurl,submitData){
    pushUrl(baseurl, submitData);
    $('#table_minion').DataTable(getDataTableParameters(baseurl,"minion_length","table_minion_length"));
    $('#table_mount').DataTable(getDataTableParameters(baseurl,"mount_length","table_mount_length"));
    $('#ranking').DataTable(getDataTableParameters(baseurl,"r_length","ranking_length"));
    var submit = decodeURIComponent(window.location.search.substring(1));
    return submit;
}

function getDataTableParameters(baseurl, lengthParam,lengthSelectName){
    var length = getUrlParameter(lengthParam);
    var page = getUrlParameter("page");
    var displayStart = length === undefined || page === undefined ? 0 : page*length;
    length = length === undefined ? 10 : length;
    return {
      "pageLength": parseInt(length),
      "displayStart": displayStart,
      "drawCallback": function( settings ) {
            var length = getUrlParameter(lengthParam);
            var submit = decodeURIComponent(window.location.search.substring(1));
            if(length === undefined){
                var length = $('select[name='+lengthSelectName+']').val();
                submit += "&"+lengthParam+"="+length;
            }
            submit = updateSubmit(submit,lengthParam,$('select[name='+lengthSelectName+']').val());
            try{
                var page = $('#ranking').DataTable().page.info().page;
                if(page !== undefined){
                    submit = updateSubmit(submit,"page",page);
                }
            }
            catch(err){}
            pushUrl(baseurl, submit);
            checkRankingSelection();
        },
        
    };
}

function getTableLength(table){
    return $('select[name=table]').val();
}

function addCharButton() {
    var name = getCookie("player_name");
    $("#user").css("display", "inline");
    $("#user").html(name);

}

function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

function updateSubmit(submit,parameter,value) {
    var oldValue = parameter+"="+getUrlParameter(parameter);
    if(!submit.includes(parameter+"=")){
        return submit + "&" + parameter+"="+value;
    }
    return submit.replace(oldValue,parameter+"="+value);
}

function browserAs() {
    if ($('#char_button').attr("class").indexOf("success") > -1) {
        var id = $(".player_id").attr('id');
        var name = $("#p_name").text();
        setCookie("player_id", id, 14);
        setCookie("player_name", name, 14);
        var jsonData = getMinionsMounts();
        setCookie("minions_mounts", jsonData, 14);
        set_char(false);
    } else {
        setCookie("player_id", "", 14);
        setCookie("player_name", "", 14);
        setCookie("minions_mounts", "", 14);
        set_char(true);
    }
    browserView();
}

function cleanAll() {
    for (i = 1; i < 300; i++) {
        var m_id = i.toString();
        if ($("#mount_" + i).length) {
            $("#mount_" + i).removeClass("success").removeClass("danger");
        }
        if ($("#minion_" + i).length) {
            $("#minion_" + i).removeClass("success").removeClass("danger");
        }
    }
}

function browserView() {
    var p_id = getCookie("player_id");
    cleanAll();
    if (p_id != "") {
        var id = $(".player_id").attr('id');
        if (p_id == id) {
            var jsonData = getMinionsMounts();
            setCookie("minions_mounts", jsonData, 14);
        }
        addCharButton();
        var json = getCookie("minions_mounts");
        var obj = JSON.parse(json);
        for (i = 1; i < 300; i++) {
            var m_id = i.toString();
            if ($("#mount_" + i).length) {
                var in_array = $.inArray(m_id, obj.mounts);
                var mount = $("[id='mount_" + i + "']");
                mount.toggleClass(function(){
                    if(in_array > -1){
                        return "success";
                    }else if($( this ).hasClass("active")){
                        return "";
                    }else{
                        return "danger";
                    }
                });
            }
            if ($("[id='minion_" + i + "']").length) {
                var minion = $("[id='minion_" + i + "']");
                minion.toggleClass(function(){
                    if($.inArray(m_id, obj.minions) > -1){
                        return "success";
                    }else if($( this ).hasClass("active")){
                        return "";
                    }else{
                        return "danger";
                    }
                });
            }
        }
        var id = $(".player_id").attr('id');
        if (p_id != id) {
            set_char(true);
        }
    } else {
        $("#user").css("display", "none");
    }
}



function getMinionsMounts() {
    var minions = [];
    var mounts = [];
    $("a").each(function(index) {
        var id = $(this).attr('id');
        if (typeof id === "undefined") {

        } else {
            if (id.startsWith("mount")) {
                mounts.push(id.replace("mount_", ""));
            }
            if (id.startsWith("minion")) {
                minions.push(id.replace("minion_", ""));
            }
        }
    });
    var collections = {
        minions: minions,
        mounts: mounts
    };
    var json = JSON.stringify(collections)
    return json;
}

function async_database_update(last_id, type, text_title) {
    var modal = $('#updateDB');
    var body = modal.find('.modal-body');
    var key = $('#key').val();
    async_call(function() {
        var loops = parseInt((last_id / 50), 10);
        for (var i = 0; i <= loops; i++) {
            var id = 1 + (i * 50);
            basicAjaxCall("update_database.php", "key=" + key + "&update=true&" + type + "=" + id,
                function(data) {}, get_language_text(text_title) + ": " + id + " - " + (id + 50), body, "post", false);
        }
    }, function() {});
}

function set_char(shoulSet) {
    var lang = getUrlParameter("lang");
    var my_char, not_my_char;
    switch (lang) {
        case "de":
            my_char = "Das ist mein Charakter";
            not_my_char = "Das ist nicht mein Charakter";
            break;
        default:
        case "en":
            my_char = "This is my charakter";
            not_my_char = "This is not my charakter";
            break;
    }
    $('#char_button').removeClass("btn-success").removeClass("btn-danger");
    $('#char_button').html(shoulSet ? my_char : not_my_char);
    $('#char_button').addClass(shoulSet ? "btn-success" : "btn-danger");

}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function async_call(fn, callback) {
    setTimeout(function() {
        fn();
        callback();
    }, 0);
}

function colorRows(type,value){
    $("tr").removeClass("success");
    $.ajax({
        url: "handler/find_players.php",
        data: type + "=" + value,
        type: 'get',
        success: function(data) {
            var obj = JSON.parse(data);
            obj.forEach(function(id) {
                $("#" + id).addClass("success");
            });
        }
    });
}

function checkRankingSelection(){
    var value = $("#find_mount").val();
    if(value != "" && value !== undefined){
        colorRows("mount",value);
        return;
    }
    value = $("#find_minion").val();
    if(value != "" && value !== undefined){
        colorRows("minion",value);
        return;
    }
}