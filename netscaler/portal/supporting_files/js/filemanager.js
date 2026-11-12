function setHeight(){
	if (navigator.userAgent.indexOf("Safari") >-1){
	    var docHeight = document.scrollHeight;
	    var footerHeight = document.getElementById('footer').scrollHeight;
	    var contentTop = document.getElementById('content').offsetTop;
	    var contentHeight = docHeight - contentTop - footerHeight;
	    document.getElementById('fm').style.height = contentHeight + 'px';
	} else {
		var x = document.getElementById('footer').offsetTop;
		var y = document.getElementById('content').offsetTop;
		var z = (x-y)-20;
		document.getElementById('fm').style.height = z + 'px';
	}
}