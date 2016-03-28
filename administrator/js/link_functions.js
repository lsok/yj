
	//上传图片前显示默认空白占位图片
		window.onload = show_default_blank_img;
	
		function show_default_blank_img () 
		{
			document.getElementById("showimg").innerHTML ="<img src='./images/link_logo.gif'>";
		}
	
	
	//Function to determine when the process_upload.php file has finished executing.
	function doneloading(theframe,thefile){
		var theloc = "link_showimg.php?thefile=" + thefile
		theframe.processajax ("showimg",theloc);
	}
	
	function uploadimg (theform){
		//Submit the form.
		theform.submit();
		//Then display a loading message to the user.
		//setStatus ("Loading...","showimg");
		setStatus ("showimg");
	}
	
	//Function to set a loading status.
	function setStatus (theObj){
		obj = document.getElementById(theObj);
		if (obj){
			//obj.innerHTML = "<div class=\"bold\">" + theStatus + "</div>";
			obj.innerHTML = "<img src='./images/link_loading.gif' style='margin:8px 0 0 30px'>";
		}
	}
	
	//此函数暂时不用
	function changesize (img, sml){
		//The display a loading message to the user.
		theobj = document.getElementById("showimg");
		if (theobj){
			setStatus ("Loading...","showimg");
			var loc = "thumb.php?img=" + img + "&sml=" + sml;
			processajax ("showimg",loc);
		}
	}