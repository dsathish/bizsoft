// A global prefix to avoid variable conflicts
var global_prefix = "ac_";

// No options key
var no_options_keys = new Array(16,17,18,19,20,33,34,35,36,37,39,45,112,113,114,115,116,117,118,119,120,121,122,123,144,145);

// source_type can be 1(JS Array) or 2(URL, which returns values with seperator)
var options_seperator = ",";

// Styles
var highlight_bg = "#3366CC";
var highlight_fg = "#FFFFFF";
var normal_bg = "#FFFFFF";
var normal_fg = "#000000";
var box_border = "#BBBBBB";
var font_size = "10px";
var options_font = "verdana,sans-serif";
var mouse_pointer = "default";
var box_padding = "0";
var option_padding = "2px";

// JavaScript classes starts here
function AutoComplete(txtObj, srcType, srcInfo, insId, multiple){
	this.instance_id = insId;
	this.text_obj = txtObj;
	this.text_obj_id = this.text_obj.id;
	this.option_prefix = global_prefix +this.text_obj_id + "_";
	this.option_div_id = global_prefix +this.text_obj_id + "_div";
	// source_type can be 1(JS Array) or 2(URL, which returns values with seperator)
	this.source_type = srcType;
	this.source_info = srcInfo;
	this.input_word = "";
	this.current_value_id = 0;
	this.ary_options = new Array();
	this.multiple = multiple;
}

// Methods for class
AutoComplete.prototype.get_values = get_values;
AutoComplete.prototype.show_options = show_options;
AutoComplete.prototype.hide_options = hide_options;
AutoComplete.prototype.display_options = display_options;
AutoComplete.prototype.generate_HTML = generate_HTML;
AutoComplete.prototype.create_div = create_div;
AutoComplete.prototype.position_div = position_div;
AutoComplete.prototype.do_highlight = do_highlight;
AutoComplete.prototype.set_value = set_value;

// Fetches the options from srcInfo
// Returns VOID
function get_values(){	
	with(this){
		if(trim(input_word)==''){
			ary_options = new Array();
			display_options();
			return true;
		}
		switch(source_type){

			case '1':
				// From JS Array
				ary_options = filter_values(source_info,input_word,multiple);
				display_options();
				break;

			case '2':
				// Assumed that the AjaxToolBox is in the Application
				var pageURL = source_info + input_word;
				//alert(pageURL);
				var status = AjaxRequest.get(
					{
						'url':pageURL
						,'onSuccess':function(req) {
								var response_text = trim(req.responseText);
								//alert(response_text);
								if(response_text != '') {
									var response_array = response_text.split(options_seperator);
									ary_options = filter_values(response_array,input_word,multiple);
									display_options();
								}
								else {
									ary_options = new Array();
									display_options();
								}
							}
						,'onError':function(req) { window.status = req.statusText+'\nContents='+req.responseText; }
					}
				);
				break;

			default:
				// default From JS Array
				ary_options = filter_values(source_info,input_word,multiple);
				display_options();
				break;
		}
	}
	return true;
}

function hide_options(){
	if(document.getElementById(this.option_div_id)) document.getElementById(this.option_div_id).style.display = "none";
}

function set_value(){
	if(this.ary_options[this.current_value_id] != undefined)
		{
		if (this.multiple == 'true')
			{
			searchstr_array = this.text_obj.value.split(",") ;
			searchstr_array.pop();
			if (searchstr_array.length > 0)
				{
				this.text_obj.value = searchstr_array.join(",") + "," + this.ary_options[this.current_value_id] + ",";
				}
			else
				{
				this.text_obj.value = searchstr_array.join(",") + this.ary_options[this.current_value_id] + ",";
				}
			}
		else
			{
			this.text_obj.value = this.ary_options[this.current_value_id];
			}
		}
	this.hide_options();
	this.text_obj.focus();
}

function show_options(e){
	// if user enters some non character keys(functional,, ctrl, alt, capslock...), do nothing
	if(is_no_options_keys(e)) return true;
	
	with(this){

		if(e.keyCode==13){		// if user enters ENTER key, put the current value
			set_value();
		}

		else if(e.keyCode==27){		// if user enters ENTER key, put the current value
			hide_options();
			return false;
		}

		else if(e.keyCode==38){		// if user enters UP keys, set the current value
			if(current_value_id >= ary_options.length-1){
				current_value_id = ary_options.length-2;
			}
			else if(current_value_id<=0) current_value_id = 0;
			else{
				current_value_id = current_value_id - 1;
			}
			do_highlight();
		}

		else if(e.keyCode==40){		// if user enters DOWN keys, set the current value			
			if(current_value_id >= ary_options.length-1){
				current_value_id = ary_options.length-1;
			}
			else if(current_value_id<=0) current_value_id = 1;
			else{
				current_value_id = current_value_id + 1;
			}
			do_highlight();
		}


		else {		// while user is typing
			var i_word = "";
			i_word = text_obj.value;
			input_word = i_word.toLowerCase();
			get_values(input_word);
		}
	}
}

function do_highlight(){
	with(this){
		for(var i=0;i<ary_options.length;i++){
			if(i==current_value_id){
				document.getElementById(option_prefix+i).style.backgroundColor = highlight_bg;
				document.getElementById(option_prefix+i).style.color = highlight_fg;
			}
			else{
				document.getElementById(option_prefix+i).style.backgroundColor = normal_bg;
				document.getElementById(option_prefix+i).style.color = normal_fg;
			}
		}
	}
}

function display_options(){
	var the_options = '';
	with(this){	
		the_options = generate_HTML();
		if(!document.getElementById(option_div_id)) { create_div();	position_div(); }
		if(trim(the_options)!=''){
			document.getElementById(option_div_id).innerHTML = the_options;
			document.getElementById(option_div_id).style.display = "block";
			document.getElementById(option_div_id).style.padding = box_padding;
			document.getElementById(option_div_id).style.overflow = "auto";
			document.getElementById(option_div_id).style.height = "100px";
			current_value_id = 0;
			do_highlight();
		}
		else{
			document.getElementById(option_div_id).style.display = "none";
		}
	}
}

function create_div()
{
	// create a new div element
	var newDiv = document.createElement("div");
	newDiv.style.position = 'absolute';
	newDiv.style.border = '1px solid #0000FF';
	newDiv.style.zIndex = '50';
	newDiv.style.textAlign = 'left';
	newDiv.id = this.option_div_id;
	//	this.text_obj.parentNode.insertBefore(newDiv, this.text_obj);
	var mybody = document.getElementsByTagName("body")[0];
	mybody.appendChild(newDiv);
}

function position_div()
{
	with(this){
		document.getElementById(option_div_id).style.width = text_obj.clientWidth+"px";
		document.getElementById(option_div_id).style.left = getX(text_obj_id)+"px";
		document.getElementById(option_div_id).style.top = getY(text_obj_id)+"px";
	}
}

function generate_HTML(){
	var return_HTML = '';
	with(this){
		//return_HTML += "<div id='" + option_div_id + "' style=\"position:absolute; border:1px solid #0000FF;\">";
		for(var i=0; i<ary_options.length;i++){
			return_HTML += "<div style=\"padding:"+option_padding+"; cursor:"+mouse_pointer+"; background-color:"+normal_bg+"; color:"+normal_fg+"; font-family:"+options_font+"; font-size:"+font_size+";\" id='" + option_prefix + i + "' onMouseover=\"javascript:"+instance_id+".current_value_id='"+i+"'; "+instance_id+".do_highlight();\" onMouseDown=\"javascript:"+instance_id+".set_value();\" style='width:100%'>"+ary_options[i]+"</div>";
		}
		//return_HTML += "</div>";
	}
	//alert(return_HTML);
	return return_HTML;
}

// Filter the values according to the input
function filter_values(response_array,i_word,multiple){
	var result_array = new Array();
	//alert("response_array->"+response_array+" \ninput_word->"+i_word);
	for(var i=0;i<response_array.length;i++){
		if (multiple == 'true')
			{
			i_word_arr = i_word.split(",") ;
			var i_word1 = i_word_arr[i_word_arr.length - 1];
			}
		else
			{
			var i_word1 = i_word;
			}
		tmp_option = response_array[i].toLowerCase();
		//if(tmp_option.indexOf(i_word1)==0) result_array[result_array.length] = initcap(response_array[i]);
		if(tmp_option.indexOf(i_word1)==0) result_array[result_array.length] = response_array[i];
	}
	//alert(result_array);
	return result_array;
}

// Javascript Trim() function
function trim(str){
   if (str == null){return ("");}
   return str.replace(/(^\s+)|(\s+$)/g,"");
}

// Javascript InitCap() function
function initcap(str){	
	var result_str = '';
	var ary_str = str.split(' ');
	for(var j=0; j<ary_str.length; j++){
		ary_str[j] = ary_str[j].substring(0,1).toUpperCase() + ary_str[j].substring(1,ary_str[j].length).toLowerCase();
		result_str += ary_str[j];
		if(j!=(ary_str.length-1)) result_str += ' ';
	}
	return result_str;
}

//get X Position value for any given object
function getX(id){
	var o = document.getElementById(id);
	var x = o.offsetLeft;
	o = o.offsetParent;
	while(o != null){
		x += o.offsetLeft;
		o = o.offsetParent;
	}
	return x;
}

function is_no_options_keys(e){
	for(var i=0;i<no_options_keys.length;i++){
		if(e.keyCode==no_options_keys[i]) return true;
	}
	return false;
}

//get Y Position value for any given object
function getY(id){
	var o = document.getElementById(id);
	var y = o.offsetTop + o.offsetHeight;
	o = o.offsetParent;
	while(o != null){
		y += o.offsetTop;
		o = o.offsetParent;
	}
	return y;
}

//function showselects
function showSelects(){
   var elements = document.getElementsByTagName("select");
   if(bw.ie){
	   combo_status = 0;
	   for (i=0;i< elements.length;i++){		   
		elements[i].style.visibility='visible';
		}
	if(document.getElementById("reporttype1")) document.getElementById("reporttype1").style.visibility='visible';
	
   }
}

//function hideselects
function hideSelects(){
   var elements = document.getElementsByTagName("select");
   if(bw.ie){
	   combo_status = 1;
		for (i=0;i< elements.length;i++){			
		elements[i].style.visibility='hidden';
		}
		if(document.getElementById("searchable")) document.getElementById("searchable").style.visibility='visible';
   }
   if(document.getElementById("eventYear")) document.getElementById("eventYear").style.visibility='visible';
   if(document.getElementById("eventMonth")) document.getElementById("eventMonth").style.visibility='visible';
   if(document.getElementById("eventDate")) document.getElementById("eventDate").style.visibility='visible';
}
