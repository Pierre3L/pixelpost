// SVN file version:
// $Id: script.js 233 2007-04-08 07:20:51Z blinking8s $

function flip(rid)
{
    current=(document.getElementById(rid).style.display == 'none') ? 'block' : 'none';
    document.getElementById(rid).style.display = current;
}

function ondatechange()
{
	var a=document.getElementById("specificdate");
	a.checked = true;
}

function invertselection(form)
{
	for (i = 0, n = form.elements.length; i < n; i++)
	{
		if(form.elements[i].type == "checkbox")
		{
			form.elements[i].checked = !form.elements[i].checked;
		}
	}
}

function checkAll(form)
{
	for (i = 0, n = form.elements.length; i < n; i++)
	{
		if(form.elements[i].type == "checkbox")
		{
			form.elements[i].checked = true;
		}
	}
}

function clearAll(form)
{
	for (i = 0, n = form.elements.length; i < n; i++)
	{
		if(form.elements[i].type == "checkbox")
		{
			form.elements[i].checked = false;
		}
	}
}
