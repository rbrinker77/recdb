var myinputs = ["recName","ingred[]","instruct[]"];

window.addEventListener("dragover",function(e){
  e = e || event;
	if (myinputs.indexOf(e.target.id) == -1) {
  	e.preventDefault();
	}
},false);
window.addEventListener("drop",function(e){
  e = e || event;
	if (myinputs.indexOf(e.target.id) == -1) {
  	e.preventDefault();
	}
},false);

function showtable(tblname) {
  document.getElementById("show"+tblname).style.visibility = "hidden";
  document.getElementById("hide"+tblname).style.visibility = "visible";
  document.getElementById(tblname+"Table").style.visibility = "visible";
}

function hidetable(tblname) {
  document.getElementById("show"+tblname).style.visibility = "visible";
  document.getElementById("hide"+tblname).style.visibility = "hidden";
  document.getElementById(tblname+"Table").style.visibility = "hidden";
}
