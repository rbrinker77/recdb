window.addEventListener("dragover",function(e){
  e = e || event;
  e.preventDefault();
},false);
window.addEventListener("drop",function(e){
  e = e || event;
  e.preventDefault();
	console.log(e.target.id);
},false);

function stopRKey(evt)
{
	var evt = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}

function copyLink(reclink) {
	copylink = document.getElementById(reclink);
	copylink.select();
	document.execCommand("Copy");
}

function setFocus()
	{
		document.getElementById("recName").focus();
    }

    var checkCount=0

//maximum number of allowed checked boxes
	var maxChecks=5

function setChecks(obj)
	{
//increment/decrement checkCount
		if(obj.checked)
		{
			checkCount=checkCount+1
		}else{
			checkCount=checkCount-1
		}
//if they checked a 4th box, uncheck the box, then decrement checkcount and pop alert
		if (checkCount>maxChecks)
		{
			obj.checked=false
			checkCount=checkCount-1
			alert('Only '+maxChecks+' categories are allowed')
		}
	}

function validateRecipe(formName)
{
	var goodIngred = "no";
	var goodInstruct = "no";
	var goodCat = "no";
	var i;

	var recName = document.forms[formName]["recName"].value;
	var ingredients = document.forms[formName]["ingred[]"];
	var instructions = document.forms[formName]["instruct[]"];
	var categories = document.forms[formName]["cat[]"];

	if (recName == null || recName == "")
	{
		alert("Recipe name must be filled out!");
		document.forms[formName]["recName"].focus();
		return false;
	}
	else if (goodIngred == "no")
	{
		for (i = 0; i < ingredients.length; i++)
		{

			if (ingredients[i].value != null && ingredients[i].value != "")
			{
				goodIngred = "yes";
			}
		}

		if (goodIngred == "no")
		{
			alert("Ingredients must be filled out!");
			ingredients[0].focus();
			return false;
		}
		else
		{
			for (i = 0; i < instructions.length; i++)
			{
				if (instructions[i].value != null || instructions[i].value != "")
				{
					goodInstruct = "yes";
				}
			}

			if (goodInstruct == "no")
			{
				alert("Instructions must be filled out!");
				instructions[0].focus();
				return false;
			}
			else
			{
				for (i = 0; i < categories.length; i++)
				{
					if (categories[i].checked == true)
					{
						goodCat = "yes";
					}
				}

				if (goodCat == "no")
				{
					alert("At least one Category must be selected!");
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}
}

function confirmDelete()
{
	var r=confirm("DELETE THIS RECIPE?", {focus:1});

	if (r==true)
	{

	}
	else
	{
		return false;
	}
}
