/*  (c) Copyright Alan Tiller 2020. All rights reserved.
 *	Intended Application: Virtual Hut
 *	Unauthorized copying of this file, via any medium is strictly prohibited
 *	Proprietary and confidential
 */

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
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


/********* Page Script *********/
$( document ).ready(function($) {
    
  /********* Dark Mode Handler *********/
  $("#dark-mode").click(function() {
    $("body").toggleClass("dark");
    $("#dark-mode-checkbox").prop('checked', $("body").hasClass('dark'));
    document.cookie = "darkmode=" + $("body").hasClass('dark') + ";expires=Thu, 01 Jan 2099 00:00:00 UTC;;path=/";
  });
    
  if (getCookie("darkmode") == "true") {
    $("body").addClass("dark");
    $("#dark-mode-checkbox").prop('checked', true);
  }

});


function show_hide_column(col_no, do_show) {
  var tbl = document.getElementById('ViewItems');
  var col = tbl.getElementsByTagName('col')[col_no];
  if (col) {
    col.style.visibility=do_show?"":"collapse";
  }
}