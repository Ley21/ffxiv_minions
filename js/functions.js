var languageTexts = 
{
    'update_message': {en:"Charakter will be update from lodestone",fr:"",de:"Der Charakter wird vom Lodestone aktuallisiert",ja:""},
    'load_char_message':{en:"Loading Charakter",fr:"",de:"Charakter wird geladen",ja:""},
    'load_ranking':{en:"Loading Ranking",fr:"",de:"Rangliste wird geladen",ja:""},
    'load_freecompany':{en:"Loading Free Company Ranking",fr:"",de:"Freie Gesellschafts Rangliste wird geladen",ja:""},
    'load_minions':{en:"Loading Minions",fr:"",de:"Begleiter werden geladen",ja:""},
    'load_mounts':{en:"Loading Mounts",fr:"",de:"Reittiere werden geladen",ja:""},
};

function get_language_text(key){
    var lang = getUrlParameter("lang");
    var text = languageTexts[key][lang];
    return text == "" ? languageTexts[key].en : text;
}

function pushUrl(type,urlData){
  window.history.pushState("object or string", "", "/"+type+"?"+urlData);
}

function loadCharakter(id) {
    //$('#content').html("<center>Loading your Minions form database and lodestone...</center>");
    var submit = getLangData() +"&"+"id="+id;
    checkCharakter(submit,function(update){
        ajaxCall("char","charakter.php",submit,function(data){},
            update ? get_language_text("update_message") : get_language_text("load_char_message"));
    });
    
}

function checkCharakter(submit,func){
    ajaxCall("","check_charakter.php",submit,function(data){
        func(data.indexOf("true") > -1);
    },null);
}

function searchCharakter(formData) {
    checkCharakter(formData,function(update){
        ajaxCall("char","charakter.php",formData,function(data){},
            update ? get_language_text("update_message") : get_language_text("load_char_message"));
    });
}

function updateCharakter(id){
    loadingMessage();
    $.ajax
    ({ 
      url: "caller/update_charakter.php",
      data: "id="+id,
      type: 'get',
      success: function(data)
      {
          var submit = decodeURIComponent(window.location.search.substring(1));
          loadFreeCompany(submit);
      }
    });
    
}

function getLangData(){
  var lang = getUrlParameter("lang");
  lang = lang ? lang : "en"
  return "lang="+lang;
}

function loadRanking(submit) {
    
    //$('#content').html("<center><h2>Loading Ranking...</h2></center>");
    ajaxCall("ranking","ranking.php",submit,function(data){},get_language_text("load_ranking"));
}

function loadFreeCompany(submit) {
  
    //$('#content').html("<center><h2>Loading Ranking...</h2></center>");
    ajaxCall("freeCompany","fc_ranking.php",submit,function(data){},get_language_text("load_freecompany"));
}

function loadMinions(submit) {
    //$('#content').html("<center><h2>Loading Minions...</h2></center>");
    ajaxCall("minions","get_minions.php",submit,function(data){},get_language_text("load_minions"));
}

function loadMounts(submit) {
    //$('#content').html("<center><h2>Loading Mounts...</h2></center>");
    ajaxCall("mounts","get_mounts.php",submit,function(data){},get_language_text("load_mounts"));
}

function loadingMessage(customMessage = ""){
    $('#content').html("<p><center><img src='img/gears.gif'><h2>"+customMessage+"</h2></center></p>");
}

function ajaxCall(baseurl,url,submitData,func,customMessage = ""){
    $('#content').html("");
    if(customMessage != null){
        loadingMessage(customMessage);
    }
  $.ajax
  ({ 
      url: "handler/"+url,
      data: submitData,
      type: 'get',
      success: function(data)
      {
        
         $('#content').html(data);
         func(data);
         pushUrl(baseurl,submitData);
         $('.table').DataTable();
         XIVDBTooltips.initialize();
         id = getCookie("player_id");
         set_char(id == "");
         browserView();
      }
  });
}

function addCharButton(){
    var name = getCookie("player_name");
    var html = '<span class="glyphicon glyphicon-user" aria-hidden="true"/> ';
    html += name;
    $("#user").css("display","inline");
    $("#user").html(html);
    
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

function browserAs(){
    if($('#char_button').attr("class").indexOf("success") > -1){
        var id = $(".player_id").attr('id');
        var name = $("#p_name").text();
        setCookie("player_id",id,14);
        setCookie("player_name",name,14);
        var jsonData = getMinionsMounts();
        setCookie("minions_mounts",jsonData,14);
        set_char(false);
      }else{
        setCookie("player_id","",14);
        setCookie("player_name","",14);
        setCookie("minions_mounts","",14);
        set_char(true);
      }
    browserView(); 
}

function cleanAll(){
    for (i = 1; i < 300; i++) {
        var m_id = i.toString();
        if ( $( "#mount_"+i ).length ) {
         $( "#mount_"+i ).removeClass("success" ).removeClass("danger" );
        }
        if ( $( "#minion_"+i ).length ) {
         $( "#minion_"+i ).removeClass("success" ).removeClass("danger" );
        }
    }
}

function browserView(){
    var p_id = getCookie("player_id");
    cleanAll();
    if(p_id != ""){
        var id = $(".player_id").attr('id');
        if(p_id == id){
            var jsonData = getMinionsMounts();
            setCookie("minions_mounts",jsonData,14);
        }
        addCharButton();
        var json = getCookie("minions_mounts");
        var obj = JSON.parse(json);
        for (i = 1; i < 300; i++) {
            var m_id = i.toString();
            if ( $( "#mount_"+i ).length ) {
             var in_array = $.inArray(m_id,obj.mounts);
             if(in_array > -1){
                 $("#mount_"+i).addClass( "success");
             }
             else{
                 $("#mount_"+i).addClass( "danger");
             }
            }
            if ( $( "#minion_"+i ).length ) {
             if($.inArray(m_id,obj.minions) > -1){
                 $("#minion_"+i).addClass( "success");
             }
             else{
                 $("#minion_"+i).addClass( "danger");
             }
            }
        }
        var id = $(".player_id").attr('id');
        if(p_id != id){
            set_char(true);
        }
    }
    else{
        $("#user").css("display","none");
    }
}

function getMinionsMounts(){
    var minions = [];
    var mounts = [];
    $( "a" ).each(function( index ) {
      var id = $( this ).attr('id');
      if (typeof id === "undefined") {
            
      }
      else{
          if(id.startsWith("mount")){
            mounts.push(id.replace("mount_",""));
          }
          if(id.startsWith("minion")){
            minions.push(id.replace("minion_",""));  
          }
      }
    });
    var collections = {minions:minions, mounts:mounts}; 
    var json = JSON.stringify(collections)
    return json;
}

function set_char(shoulSet){
    var lang = getUrlParameter("lang");
    var my_char, not_my_char;
    switch(lang){
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
    $('#char_button').removeClass("btn-success" ).removeClass("btn-danger" );
    $('#char_button').html(shoulSet ? my_char : not_my_char);
    $('#char_button').addClass( shoulSet ? "btn-success" : "btn-danger");
    
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
} 

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}