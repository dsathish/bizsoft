var row_id = 0;
var eventobj_product = new Array();
var eventobj_tax = new Array();
function addRow(form_name, type)
{
	var tr = document.createElement('TR');
	
	if (type == undefined)
	{
		var num = 0;
		if(typeof refer_arr != 'undefined')
		{
			var td = document.createElement('TD');
			td.innerHTML = '<select id="refer_id['+row_id+']" name="refer_id['+row_id+']" style="width:200px" >' + returnOptions(refer_arr, 'refer_id', 'refer_desc') + '</select>';
			tr.appendChild(td);
		}

		var td = document.createElement('TD');
		td.innerHTML = '<input type="text" id="product_desc['+row_id+']" name="product_desc['+row_id+']" autocomplete="off" onKeyPress="return disableEnterKey(event);" onkeyup="javascript:eventobj_product['+row_id+'].show_options(event);" onblur="javascript:eventobj_product['+row_id+'].hide_options();" size="20" class="required" />';
		tr.appendChild(td);
	
		var td = document.createElement('TD');
		td.innerHTML = '<input type="text" id="quantity['+row_id+']" name="quantity['+row_id+']" value="" size="10" class="required number" />';
		tr.appendChild(td);

		var td = document.createElement('TD');
		td.innerHTML = '<select id="uom_id['+row_id+']" name="uom_id['+row_id+']" width="20px" class="required" >' + returnOptions(uom_arr, 'uom_id', 'uom_desc') + '</select>';
		tr.appendChild(td);
	
		var td = document.createElement('TD');
		td.innerHTML = '<input type="text" id="price['+row_id+']" name="price['+row_id+']" value="" size="7" maxlength="7" class="number" />';
		tr.appendChild(td);
	
		var td = document.createElement('TD');
		td.innerHTML = '<input type="button" id="delete['+row_id+']" name="delete['+row_id+']" value="Delete" onclick="deleteRow(\''+form_name+'\',this,'+num+');" />';
		tr.appendChild(td);
	
		document.getElementById(form_name).tBodies[num].appendChild(tr);
		eventobj_product[row_id] = new AutoComplete(document.getElementById('product_desc['+ row_id +']'), '1', product_array, 'eventobj_product['+ row_id +']');
	}
	else if (type == 'tax_table')
	{
		var num = 1;
		var td = document.createElement('TD');
		td.innerHTML = '<select id="tax_id['+row_id+']" name="tax_id['+row_id+']" width="60%" class="required" >' + returnOptions(tax_arr, 'tax_id', 'tax_desc') + '</select>';
		tr.appendChild(td);
	
		var td = document.createElement('TD');
		td.innerHTML = '<input type="text" id="tax_rate['+row_id+']" name="tax_rate['+row_id+']" value="" size="10" maxlength="10" class="required number" />';
		tr.appendChild(td);
	
		var td = document.createElement('TD');
		td.innerHTML = '<input type="button" id="delete_tax['+row_id+']" name="delete_tax['+row_id+']" value="Delete" onclick="deleteRow(\''+form_name+'\',this,'+num+');" />';
		tr.appendChild(td);

		document.getElementById(form_name).tBodies[num].appendChild(tr);
	}
	else if (type == 'payment')
	{
		var num = 1;
		var td = document.createElement('TD');
		td.innerHTML = '<select id="payment_items['+row_id+']" name="payment_items['+row_id+']" class="required">' + returnOptions(refer_arr,'refer_id','refer_desc') + '</select>';
		tr.appendChild(td);
	
		var td = document.createElement('TD');
		td.innerHTML = '<input type="text" id="amount['+row_id+']" name="amount['+row_id+']" value="" size="10" maxlength="10" class="required number" />';
		tr.appendChild(td);

		var td = document.createElement('TD');
		td.innerHTML = '<input type="text" id="deductions['+row_id+']" name="deductions['+row_id+']" value="" size="10" maxlength="10" class="required number" />';
		tr.appendChild(td);

		var td = document.createElement('TD');
		td.innerHTML = '<input type="button" id="delete_item['+row_id+']" name="delete_item['+row_id+']" value="Delete" onclick="deleteRow(\''+form_name+'\',this,'+num+');" />';
		tr.appendChild(td);

		document.getElementById(form_name).tBodies[num].appendChild(tr);
	}
	row_id = row_id + 1;
}

function deleteRow(form_name,id,num)
{
	var row = id.parentNode.parentNode;
	document.getElementById(form_name).tBodies[num].removeChild(row);
}

function disableEnterKey(e)
{
	var key;
	if(window.event)
		key = window.event.keyCode; //IE
	else
		key = e.which; //firefox
	return (key != 13);
}

function confirm_del(url)
{
	var del= confirm("Do you really want to Delete?");
	if (del == true)
	{
		window.location = url;
	}
}

function returnOptions(arr, id, val, def)
{
	if(def == undefined)
	{
		var def = '';
	}

	var str = '<option value="">Select</option>';
	for(var i = 0, cnt = arr.length; i < cnt; i++)
	{
		if(arr[i][id] == def)
		{
			str += '<option value='+ arr[i][id] +' selected>' + arr[i][val] + '</option>'+"\n";
		}
		else
		{
			str += '<option value='+ arr[i][id] +'>' + arr[i][val] + '</option>'+"\n";
		}
	}

	return str;
}

//function to check empty text fields
function nullCheck(fldId,message) 
{
	var objValue = document.getElementById(fldId).value;
	if( (objValue == "") || (Trim(objValue))){
		retunMessage(message,fldId);
	}
	else{
		return true;
	}
}

//function to check length
function checkLen(len,fldId,message) 
{
	var objValue = document.getElementById(fldId).value;
	var length = objValue.length
	if(length < len)
		retunMessage(message,fldId);
	else
		return true;
}

//function for removing white spaces
function Trim(txt){
	if(txt.split(" ").join("").length == 0)
		return true;
	else
		return false;
}

//function to alert error messages for text fields
function retunMessage(message,fldId) 
{
	alert(message);
	document.getElementById(fldId).focus();
	return false;
}
