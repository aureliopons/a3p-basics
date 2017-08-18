function setCookie(cname, cvalue) {
    var d = new Date();
	var exdays = 100000;
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
	var path = "path=/";
    document.cookie = cname + "=" + cvalue + "; " + expires + "; " + path;
	alert(1);
}