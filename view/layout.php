<!DOCTYPE html>
<html lang="en" class="">
<!--
* Layout onde sao inseridos os formularios e views gerais
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Urna APP Sindicato</title>

	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<img src="img/logo.png" class="logo"/>
	<div class="main ">
		<!--view-->
		<?php include($url); ?>
	</div>
	<script>
		function checarBranco(e){
			var key = window.event ? e.keyCode : e.which;
			if(key==66){
				document.querySelector("form").reset();
			  var x=document.getElementsByTagName("input"); // plural
			  for(var i = 0; i < x.length; i++) {
			    x[i].value=0;
			  }
			}
		}
		function nextInput(e) {
    		var key = window.event ? e.keyCode : e.which;
    		if(key==66){
  				document.querySelector("form").reset();
				  var x=document.getElementsByTagName("input"); // plural
				  for(var i = 0; i < x.length; i++) {
				    x[i].value=0;
				  }
    		}
    		else{
			    var target = e.srcElement || e.target;
			    target.value=target.value.replace(/[^\d]/,'')
			    var maxLength = parseInt(target.attributes["maxlength"].value, 10);
			    var myLength = target.value.length;
			    if (myLength >= maxLength) {
			        var next = target;
			        while (next = next.nextElementSibling) {
			            if (next == null)
			                break;
			            if (next.tagName.toLowerCase() === "input") {
			                next.focus();
			                break;
			            }
			        }
			    }
			    // Move to previous field if empty (user pressed backspace)
			    else if (myLength === 0) {
			    	if (key==8 || key==46){
			    		document.querySelector("form").reset();
			    		document.querySelector("input").focus();
			    	}
			    	else{
				        var previous = target;
				        while (previous = previous.previousElementSibling) {
				            if (previous == null)
				                break;
				            if (previous.tagName.toLowerCase() === "input") {
				                previous.focus();
				                break;
				            }
				        }
				    }
			    }
			  }
        return true;
		}

		function ajax(url,success){
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			  if (this.readyState == 4 && this.status == 200) {
			  	success(this.responseText);
			  }
			};
			xhttp.open("GET", url, true);
			xhttp.send();
		}
	</script>
	<script language="javascript" type="text/javascript">
		// disable right button
		(function (global) {
		    var _hash = "!";
		    var noBackPlease = function () {
		        global.location.href += "#";
		        global.setTimeout(function () {
		            global.location.href += "!";
		        }, 50);
		    };

		    global.onhashchange = function () {
		        if (global.location.hash !== _hash) {
		            global.location.hash = _hash;
		        }
		    };

		    global.onload = function () {            
		        noBackPlease();
		        // disables backspace on page except on input fields and textarea..
		    }
		})(window);
		document.addEventListener('contextmenu', event => event.preventDefault());
	    //this code handles the F5/Ctrl+F5/Ctrl+R
	    // window.history.forward(1);
	    document.onkeydown = checkKeycode;
	    function checkKeycode(e) {
	        var keycode;
	        if (window.event)
	            keycode = window.event.keyCode;
	        else if (e)
	            keycode = e.which;
	        
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
	        // Mozilla firefox
	        // if ($.browser.mozilla) {
	            if (keycode == 116 ||(e.ctrlKey && keycode == 82)) {
	                if (e.preventDefault)
	                {
	                    e.preventDefault();
	                    e.stopPropagation();
	                }
	            }
	        // } 
	        // IE
	        // else if ($.browser.msie) {
	            if (keycode == 116 || (window.event.ctrlKey && keycode == 82)) {
	                window.event.returnValue = false;
	                window.event.keyCode = 0;
	                window.status = "Refresh is disabled";
	            }
	        // }
	    }
	</script>
</body>
</html>