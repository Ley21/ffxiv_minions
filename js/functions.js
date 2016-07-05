function pushUrl(type,urlData){
  window.history.pushState("object or string", "", "/"+type+"?"+urlData);
}
function loadCharakter(id) {
    $('#content').html("<center>Loading your Minions form database and lodestone...</center>");
    ajaxCall("char","charakter.php",getLangData() +"&"+"id="+id,function(data){});
    
}

function searchCharakter(formData) {
    $('#content').html("<center>Loading your Minions form database and lodestone...</center>");
    ajaxCall("char","charakter.php",getLangData() +"&"+formData,function(data){});
}


function getLangData(){
  var lang = getUrlParameter("lang");
  lang = lang ? lang : "en"
  return "lang="+lang;
}


function loadRanking() {
  
    $('#content').html("<center><h2>Loading Ranking...</h2></center>");
    ajaxCall("ranking","ranking.php",getLangData(),function(data){});
}

function loadMinions(submit) {
    $('#content').html("<center><h2>Loading Minions...</h2></center>");
    ajaxCall("minions","get_minions.php",submit,function(data){});
}

function loadMounts(submit) {
    $('#content').html("<center><h2>Loading Mounts...</h2></center>");
    ajaxCall("mounts","get_mounts.php",submit,function(data){});
}

function ajaxCall(baseurl,url,submitData,func){
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
         //XIVDBTooltips.setOptions("undefined" != typeof xivdb_tooltips ? xivdb_tooltips : xivdb_tooltips_default);
         XIVDBTooltips.initialize();
      }
  });
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
};