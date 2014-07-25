function display_image () {
	var path=document.getElementById('tou').value;
	for(var i=0;i<10;++i)
	{
		path=path.replace("\\","/");
	}
	document.getElementById('displaytou').src=path;
	alert(path);
}