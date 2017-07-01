function limitchar(myfield, e, dec)
{
	var key;
	var keychar;

	if (window.event)
		key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;
		keychar = String.fromCharCode(key);

// control keys
	if ((key==null) || (key==0) || (key==8) || 
		(key==9) || (key==13) || (key==27) )
		return true;

// numbers
	else if ((("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/-.,;&°() ").indexOf(keychar) > -1))
		return true;

// decimal point jump
	else if (dec && (keychar == "."))
		{
		myfield.form.elements[dec].focus();
		return false;
	}
	else
		return false;
}

function stopRKey(evt)
{
	var evt = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
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
			var stringCleaning;

			if (ingredients[i].value != null && ingredients[i].value != "")
			{
				goodIngred = "yes";
				stringCleaning = ingredients[i].value;

				ingredients[i].value = ingredients[i].value.replace(/½/g," 1/2 ");
				ingredients[i].value = ingredients[i].value.replace(/¼/g," 1/4 ");
				ingredients[i].value = ingredients[i].value.replace(/¾/g," 3/4 ");
				ingredients[i].value = ingredients[i].value.replace(/⅓/g," 1/3 ");
				ingredients[i].value = ingredients[i].value.replace(/⅔/g," 2/3 ");
				ingredients[i].value = ingredients[i].value.replace(/⅛/g," 1/8 ");
				ingredients[i].value = ingredients[i].value.replace(/⅜/g," 3/8 ");
				ingredients[i].value = ingredients[i].value.replace(/⅝/g," 5/8 ");
				ingredients[i].value = ingredients[i].value.replace(/⅞/g," 7/8 ");
				ingredients[i].value = ingredients[i].value.replace(/  /g," ");
//alert(ingredients[i].value +"=in5");
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
				
					instructions[i].value.replace("½"," 1/2 ");
					instructions[i].value.replace("¼"," 1/4 ");
					instructions[i].value.replace("¾"," 3/4 ");
					instructions[i].value.replace("⅓"," 1/3 ");
					instructions[i].value.replace("⅔"," 2/3 ");
					instructions[i].value.replace("⅛"," 1/8 ");
					instructions[i].value.replace("⅜"," 3/8 ");
					instructions[i].value.replace("⅝"," 5/8 ");
					instructions[i].value.replace("⅞"," 7/8 ");
					instructions[i].value.replace("  "," ");
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