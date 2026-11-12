function  updateAdmin(){
	for( $i = 0; $i < document.values.elements.length; $i++ ){
		if(document.values.elements[$i].name.indexOf("font") < 0){
			updateSample(document.values.elements[$i].name);
		} 
	}	
	comma = document.values.elements["font-family"].value.indexOf(",");
	if (comma > -1){
	  fontfam = document.values.elements["font-family"].value.substring(0,comma);
	} else {
	  fontfam = document.values.elements["font-family"].value;
	}
	document.values.elements["font-family"].value = fontfam;
	setFontFamRadio(fontfam);
	setFontSizeRadio(document.values.elements["font-size"].value);
	changeit("fontsize", "font-family", document.values.elements["font-family"].value);
	changeit("fontsize", "font-size", document.values.elements["font-size"].value);
	changeit("preview", "font-family", document.values.elements["font-family"].value);
	changeit("preview", "font-size", document.values.elements["font-size"].value);
}

function setBg(box){
	color=box.style.backgroundColor;
	var selected=document.selectorform.items.value;
	if (selected != ""){
	  document.values.elements[selected].value=color;
	} else {
	  alert("You must select an Item first.");
	  return;
	}
	
	updateSample(selected);
}
function changeit(elementName, propertyName , value){
  if (navigator.userAgent.indexOf("MSIE") >-1){
  	  if (propertyName == "font-family"){
  	    propertyName = "fontFamily";
  	  }
 	  if (propertyName == "font-size"){
  	    propertyName = "fontSize";
  	  }
	  document.getElementById(elementName).style.setAttribute(propertyName, value, "");
  } else {
	  //alert('document.getElementById('+elementName+').style.setProperty('+propertyName+', '+value+', "")');
	  document.getElementById(elementName).style.setProperty(propertyName, value, "");
  }
}
function updateSample(selected){
    if (selected == "submit" || selected == "theme"){
      return;
    }
	thing = selected.split("-")
	if (thing[1] == "border"){
	  if (navigator.userAgent.indexOf("MSIE") >-1){
	    thing[1] = "borderColor";
	  } else {
  	    thing[1] = "border-color";
	  }
	} 	
	changeit(thing[0], thing[1], document.values.elements[selected].value);
}
function setfont(){
  var pick;
  var family = document.fontform.family; 
  for (var i = 0; i < family.length; i++){
    if (family[i].checked){
      pick = family[i].value;
    }
  }
  document.values.elements["font-family"].value=pick;
  changeit("preview", "font-family", pick);
  changeit("fontsize", "font-family", pick);
}
function setsize(){
  var pick;
  var family = document.fontform.size;
  for (var i = 0; i < family.length; i++){
    if (family[i].checked){
      pick = family[i].value;
    }
  }
  document.values.elements["font-size"].value=pick;
  changeit("preview", "font-size", pick);
  changeit("fontsize", "font-size", pick);
}
function populateFields(){
	qs = window.location.search.substring(1,window.location.search.length);
	kvp = qs.split("&");
	var GET = new Array();
	for( $i = 0; $i < kvp.length; $i++ ){
		 var vals = kvp[$i].split("=");
		 if (vals[0].indexOf("-") > -1){
		 	document.values.elements[vals[0]].value=unescape(vals[1]);
		 	//alert(vals[0] + ": " + vals[1]);
		 }
	}
	for( $i = 0; $i < document.values.elements.length; $i++ ){
		if(document.values.elements[$i].name.indexOf("font") < 0){
			updateSample(document.values.elements[$i].name);
		} 
	}	
	comma = document.values.elements["font-family"].value.indexOf(",");
	if (comma > -1){
	  fontfam = document.values.elements["font-family"].value.substring(0,comma);
	} else {
	  fontfam = document.values.elements["font-family"].value;
	}
	document.values.elements["font-family"].value = fontfam;
	setFontFamRadio(fontfam);
	setFontSizeRadio(document.values.elements["font-size"].value);
	changeit("fontsize", "font-family", document.values.elements["font-family"].value);
    changeit("fontsize", "font-size", document.values.elements["font-size"].value);
	changeit("preview", "font-family", document.values.elements["font-family"].value);
	changeit("preview", "font-size", document.values.elements["font-size"].value);
}
function setFontSizeRadio(fontsize){
  if (fontsize == ""){
    fontsize = "medium";
  }
  var size = document.fontform.size;
  for (var i = 0; i < size.length; i++){
    if (size[i].value.toLowerCase() == fontsize.toLowerCase()){
      size[i].checked = true;
    }
  }
}
function setFontFamRadio(fontfam){
  var family = document.fontform.family;
  for (var i = 0; i < family.length; i++){
    if (family[i].value.toLowerCase() == fontfam.toLowerCase()){
      family[i].checked = true;
    }
  }
}