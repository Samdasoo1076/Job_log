/***** Blur *******************************************************************************************/
// 자바스크립트에서 사용하는 전역변수 선언
var g4_path      = "..";
var g4_bbs       = "bbs";
var g4_bbs_img   = "img";
var g4_url       = "";
var g4_charset   = "utf-8";
var g4_cookie_domain = "";
var g4_is_gecko  = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
var g4_is_ie     = navigator.userAgent.toLowerCase().indexOf("msie") != -1;

/*
var myAnchors=document.all.tags("A");
function allblur() {
	for (i=0;i<myAnchors.length;i++) {
		myAnchors[i].onfocus=new Function("blur()");
	}
}

allblur();
*/
function bluring(){
	if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
}

document.onfocusin=bluring;

/***** Img RollOver ****************************************************************************************/

function imageOver(imgEl) {
	imgEl.src = imgEl.src.replace(".gif", "_on.gif");
}

function imageOut(imgEl) {
	imgEl.src = imgEl.src.replace("_on.gif", ".gif");
}

/***** Min Width ****************************************************************************************/

function BodyMinSize() {
	var mwid = document.getElementById("mwidthwrap").style;
	if(document.body.clientWidth <= 1024) {
		mwid.width = "1024px";
	} else {
		mwid.width = "100%";
	}
}

/**
* 익스플로러 7 미만에서 png 파일을 처리하기 위함
* css 에 png24 필요	: *.png24 {tmp:expression(setPng24(this)); }
* 사용법 : <img src="image.png" class="png24">
*/
function setPng24(obj)
{
	if (navigator.userAgent.toLowerCase().indexOf("msie 7") < 0) {
		obj.width = obj.height = 1;
		obj.className = obj.className.replace(/\bpng24\b/i,'');
		obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
		obj.src = "";
		return "";
	}
}


String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");   
}

String.prototype.ltrim = function() {   
	return this.replace(/^\s+/,"");      
}   
      
String.prototype.rtrim = function() {   
	return this.replace(/\s+$/,"");      
} 

String.prototype.replaceall = function(_findValue, _replaceValue) {
 return this.replace(new RegExp(_findValue,"g"), _replaceValue);
};

function fc_chk_byte(aro_name,ari_max)
{

	var ls_str     = aro_name.value; // 이벤트가 일어난 컨트롤의 value 값
	var li_str_len = ls_str.length;  // 전체길이

	// 변수초기화
	var li_max      = ari_max; // 제한할 글자수 크기
	var i           = 0;  // for문에 사용
	var li_byte     = 0;  // 한글일경우는 2 그밗에는 1을 더함
	var li_len      = 0;  // substring하기 위해서 사용
	var ls_one_char = ""; // 한글자씩 검사한다
	var ls_str2     = ""; // 글자수를 초과하면 제한할수 글자전까지만 보여준다.

	for(i=0; i< li_str_len; i++)
	{
		// 한글자추출
		ls_one_char = ls_str.charAt(i);

		// 한글이면 2를 더한다.
		if (escape(ls_one_char).length > 4)
		{
			li_byte = li_byte+2;
		}
      // 그외의 경우는 1을 더한다.
		else
    {
			li_byte++;
		}

		// 전체 크기가 li_max를 넘지않으면
		if(li_byte <= li_max)
		{
			li_len = i + 1;
		}
	}
   
	// 전체길이를 초과하면
	if(li_byte > li_max)
	{
		alert( li_max + " 글자를 초과 입력할수 없습니다. \n 초과된 내용은 자동으로 삭제 됩니다. ");
		ls_str2 = ls_str.substr(0, li_len);
		aro_name.value = ls_str2;
 
	}
	aro_name.focus();   
}

function chk_file_size(max, obj) {
	
	var maxSize = max * 1024 * 1024;
	var fileSize = 0;

	var b = navigator.appName;
	
	if (b == "Microsoft Internet Explorer") {
		var oas = new ActiveXObject("Scripting.FileSystemObject");
		fileSize = oas.getFile( obj.value ).size;
	} else {
		fileSize = obj.files[0].size;
	}
	
	//alert(fileSize);

	if (maxSize >= fileSize) {
		return true;
	} else {
		return false;
	}
}

function isNull(str) {
	str = str.replace(/\s/g, "");
	return ((str == null || str == "" || str == "<undefined>" || str == "undefined") ? true:false);
}

function NewWindow(mypage, myname, w, h, scroll) {

	if( (navigator.userAgent.indexOf("Firefox") > 0) || (navigator.userAgent.indexOf("Safari") > 0) || (navigator.userAgent.indexOf("Chrome") > 0) ){
		//
	} else {
		mypage = mypage.replace("add_image_up.php", "add_image_up_ie.php");
	}
	
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;

	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize=no'

	win = window.open(mypage, myname, winprops)

	if(win==null){
		alert("팝업이 차단되었습니다!"); 
	}

	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function containsCharsOnly(input,chars) {
	
	for (var inx = 0; inx < input.value.length; inx++) {

		if (chars.indexOf(input.value.charAt(inx)) == -1) {
			alert(chars + "만 입력 가능합니다..");
			
			input.value = input.value.substring(0,input.value.length -1);
			
			input.focus();
			return false;
		}
	}
	return true;	
}

// 전화번호 검사 
//
function isPhoneNumber(input) {
	var chars = "1234567890-~() ";
	return containsCharsOnly(input,chars);
}

function isNumber(input) {
	var chars = "1234567890";
	return containsCharsOnly(input,chars);
}

function isMathNumber(input) {
	var chars = "-1234567890";
	return containsCharsOnly(input,chars);
}

function isScaleNumber(input) {
	var chars = "1234567890X";
	return containsCharsOnly(input,chars);
}

function _ID(obj){return document.getElementById(obj)}

function openLayer(obj,mode) {
	obj = _ID(obj);
	if (mode) obj.style.display = mode;
	else obj.style.display = (obj.style.display!="none") ? "none" : "block";
}

function openLayer(obj,mode)
{
	obj = _ID(obj);
	if (mode) obj.style.display = mode;
	else obj.style.display = (obj.style.display!="none") ? "none" : "block";
}

/*** 레이어 팝업창 띄우기 ***/
function popupLayer(s,w,h)
{
	if (!w) w = 600;
	if (!h) h = 400;

	var pixelBorder = 3;
	var titleHeight = 12;
	w += pixelBorder * 2;
	h += pixelBorder * 2 + titleHeight;

	var bodyW = document.body.clientWidth;
	var bodyH = document.body.clientHeight;

	var posX = (bodyW - w) / 2;
	var posY = (bodyH - h) / 2;

	hiddenSelectBox('hidden');

	/*** 백그라운드 레이어 ***/
	var obj = document.createElement("div");
	with (obj.style){
		position = "absolute";
		left = 0;
		top = 0;
		width = "100%";
		height = document.body.scrollHeight;
		backgroundColor = "#000000";
		filter = "Alpha(Opacity=80)";
		opacity = "0.5";
	}
	obj.id = "objPopupLayerBg";
	document.body.appendChild(obj);

	/*** 내용프레임 레이어 ***/
	var obj = document.createElement("div");
	with (obj.style){
		position = "absolute";
		left = posX + document.body.scrollLeft;
		top = posY + document.body.scrollTop;
		width = w;
		height = h;
		backgroundColor = "#ffffff";
		border = "3px solid #000000";
	}
	obj.id = "objPopupLayer";
	document.body.appendChild(obj);

	/*** 타이틀바 레이어 ***/
	var bottom = document.createElement("div");
	with (bottom.style){
		position = "absolute";
		width = w - pixelBorder * 2;
		height = titleHeight;
		left = 0;
		top = h - titleHeight - pixelBorder * 3;
		padding = "4px 0 0 0";
		textAlign = "center";
		backgroundColor = "#000000";
		color = "#ffffff";
		font = "bold 8pt tahoma; letter-spacing:0px";
		
	}
	bottom.innerHTML = "<a href='javascript:closeLayer()' class='white'>X close</a>";
	obj.appendChild(bottom);

	/*** 아이프레임 ***/
	var ifrm = document.createElement("iframe");
	with (ifrm.style){
		width = w - 6;
		height = h - pixelBorder * 2 - titleHeight - 3;
		//border = "3 solid #000000";
	}
	ifrm.frameBorder = 0;
	ifrm.src = s;
	//ifrm.className = "scroll";
	obj.appendChild(ifrm);
}
function closeLayer()
{
	hiddenSelectBox('visible');
	_ID('objPopupLayer').parentNode.removeChild( _ID('objPopupLayer') );
	_ID('objPopupLayerBg').parentNode.removeChild( _ID('objPopupLayerBg') );
}
function hiddenSelectBox(mode)
{
	var obj = document.getElementsByTagName('select');
	for (i=0;i<obj.length;i++){
		obj[i].style.visibility = mode;
	}
}

function clear_select(obj){
	sel_len = obj.length;
	for(i = sel_len ; i > 0; i--) {
		obj.options[i] = null;
	}
	return ;
}

// 이미지의 크기에 따라 새창의 크기가 변경됩니다.
// zzzz님께서 알려주셨습니다. 2005/04/12
function image_window(img){
	
	var w = img.tmp_width; 
	var h = img.tmp_height; 
	var winl = (screen.width-w)/2; 
	var wint = (screen.height-h)/3; 
	
	if (w >= screen.width) {
		winl = 0; 
		h = (parseInt)(w * (h / w)); 
	}
	
	if (h >= screen.height) { 
		wint = 0; 
		w = (parseInt)(h * (w / h)); 
	} 

	var js_url = "<script language='JavaScript1.2'> \n"; 
		js_url += "<!-- \n"; 
		js_url += "var ie=document.all; \n"; 
		js_url += "var nn6=document.getElementById&&!document.all; \n"; 
		js_url += "var isdrag=false; \n"; 
		js_url += "var x,y; \n"; 
		js_url += "var dobj; \n"; 
		js_url += "function movemouse(e) \n"; 
		js_url += "{ \n"; 
		js_url += "  if (isdrag) \n"; 
		js_url += "  { \n"; 
		js_url += "    dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x; \n"; 
		js_url += "    dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y; \n"; 
		js_url += "    return false; \n"; 
		js_url += "  } \n"; 
		js_url += "} \n"; 
		js_url += "function selectmouse(e) \n"; 
		js_url += "{ \n"; 
		js_url += "  var fobj      = nn6 ? e.target : event.srcElement; \n"; 
		js_url += "  var topelement = nn6 ? 'HTML' : 'BODY'; \n"; 
		js_url += "  while (fobj.tagName != topelement && fobj.className != 'dragme') \n"; 
		js_url += "  { \n"; 
		js_url += "    fobj = nn6 ? fobj.parentNode : fobj.parentElement; \n"; 
		js_url += "  } \n"; 
		js_url += "  if (fobj.className=='dragme') \n"; 
		js_url += "  { \n"; 
		js_url += "    isdrag = true; \n"; 
		js_url += "    dobj = fobj; \n"; 
		js_url += "    tx = parseInt(dobj.style.left+0); \n"; 
		js_url += "    ty = parseInt(dobj.style.top+0); \n"; 
		js_url += "    x = nn6 ? e.clientX : event.clientX; \n"; 
		js_url += "    y = nn6 ? e.clientY : event.clientY; \n"; 
		js_url += "    document.onmousemove=movemouse; \n"; 
		js_url += "    return false; \n"; 
		js_url += "  } \n"; 
		js_url += "} \n"; 
		js_url += "document.onmousedown=selectmouse; \n"; 
		js_url += "document.onmouseup=new Function('isdrag=false'); \n"; 
		js_url += "//--> \n"; 
		js_url += "</"+"script> \n"; 

		var settings;
		
		if (    // 이미지의 크기에 따라 새창의 크기가 변경됩니다.
		// zzzz님께서 알려주셨습니다. 2005/04/12
			function image_window(img){
				var w = img.tmp_width; 
				var h = img.tmp_height; 
				var winl = (screen.width-w)/2; 
				var wint = (screen.height-h)/3; 

				if (w >= screen.width) { 
					winl = 0; 
					h = (parseInt)(w * (h / w)); 
				} 

				if (h >= screen.height) { 
					wint = 0; 
					w = (parseInt)(h * (w / h)); 
				} 

				var js_url = "<script language='JavaScript1.2'> \n"; 
					js_url += "<!-- \n"; 
					js_url += "var ie=document.all; \n"; 
					js_url += "var nn6=document.getElementById&&!document.all; \n"; 
					js_url += "var isdrag=false; \n"; 
					js_url += "var x,y; \n"; 
					js_url += "var dobj; \n"; 
					js_url += "function movemouse(e) \n"; 
					js_url += "{ \n"; 
					js_url += "  if (isdrag) \n"; 
					js_url += "  { \n"; 
					js_url += "    dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x; \n"; 
					js_url += "    dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y; \n"; 
					js_url += "    return false; \n"; 
					js_url += "  } \n"; 
					js_url += "} \n"; 
					js_url += "function selectmouse(e) \n"; 
					js_url += "{ \n"; 
					js_url += "  var fobj      = nn6 ? e.target : event.srcElement; \n"; 
					js_url += "  var topelement = nn6 ? 'HTML' : 'BODY'; \n"; 
					js_url += "  while (fobj.tagName != topelement && fobj.className != 'dragme') \n"; 
					js_url += "  { \n"; 
					js_url += "    fobj = nn6 ? fobj.parentNode : fobj.parentElement; \n"; 
					js_url += "  } \n"; 
					js_url += "  if (fobj.className=='dragme') \n"; 
					js_url += "  { \n"; 
					js_url += "    isdrag = true; \n"; 
					js_url += "    dobj = fobj; \n"; 
					js_url += "    tx = parseInt(dobj.style.left+0); \n"; 
					js_url += "    ty = parseInt(dobj.style.top+0); \n"; 
					js_url += "    x = nn6 ? e.clientX : event.clientX; \n"; 
					js_url += "    y = nn6 ? e.clientY : event.clientY; \n"; 
					js_url += "    document.onmousemove=movemouse; \n"; 
					js_url += "    return false; \n"; 
					js_url += "  } \n"; 
					js_url += "} \n"; 
					js_url += "document.onmousedown=selectmouse; \n"; 
					js_url += "document.onmouseup=new Function('isdrag=false'); \n"; 
					js_url += "//--> \n"; 
					js_url += "</"+"script> \n"; 

				var settings;

				if (g4_is_gecko) {
					settings  ='width='+(w+10)+','; 
					settings +='height='+(h+10)+','; 
				} else {
					settings  ='width='+w+','; 
					settings +='height='+h+','; 
				}
				settings +='top='+wint+','; 
				settings +='left='+winl+','; 
				settings +='scrollbars=no,'; 
				settings +='resizable=yes,'; 
				settings +='status=no'; 


				win=window.open("","image_window",settings); 
				win.document.open(); 
				win.document.write ("<html><head> \n<meta http-equiv='imagetoolbar' CONTENT='no'> \n<meta http-equiv='content-type' content='text/html; charset="+g4_charset+"'>\n"); 
				var size = "이미지 사이즈 : "+w+" x "+h;
				win.document.write ("<title>"+size+"</title> \n"); 
				if(w >= screen.width || h >= screen.height) { 
					win.document.write (js_url); 
					var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n 이미지 사이즈가 화면보다 큽니다. \n 왼쪽 버튼을 클릭한 후 마우스를 움직여서 보세요. \n\n 더블 클릭하면 닫혀요. '"; 
				}
			else
				var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n 클릭하면 닫혀요. '"; 
				win.document.write ("<style>.dragme{position:relative;}</style> \n"); 
				win.document.write ("</head> \n\n"); 
				win.document.write ("<body leftmargin=0 topmargin=0 bgcolor=#dddddd style='cursor:arrow;'> \n"); 
				win.document.write ("<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td align=center valign=middle><img src='"+img.src+"' width='"+w+"' height='"+h+"' border=0 class='dragme' "+click+"></td></tr></table>");
				win.document.write ("</body></html>"); 
				win.document.close(); 

			if(parseInt(navigator.appVersion) >= 4){win.window.focus();} 
		}) {
        settings  ='width='+(w+10)+','; 
        settings +='height='+(h+10)+','; 
    } else {
        settings  ='width='+w+','; 
        settings +='height='+h+','; 
    }
    settings +='top='+wint+','; 
    settings +='left='+winl+','; 
    settings +='scrollbars=no,'; 
    settings +='resizable=yes,'; 
    settings +='status=no'; 


    win=window.open("","image_window",settings); 
    win.document.open(); 
    win.document.write ("<html><head> \n<meta http-equiv='imagetoolbar' CONTENT='no'> \n<meta http-equiv='content-type' content='text/html; charset="+g4_charset+"'>\n"); 
    var size = "이미지 사이즈 : "+w+" x "+h;
    win.document.write ("<title>"+size+"</title> \n"); 
    if(w >= screen.width || h >= screen.height) { 
        win.document.write (js_url); 
        var click = "ondblclick='window.close();' style='cursor:move' title=' "+size+" \n\n 이미지 사이즈가 화면보다 큽니다. \n 왼쪽 버튼을 클릭한 후 마우스를 움직여서 보세요. \n\n 더블 클릭하면 닫혀요. '"; 
    } 
    else 
        var click = "onclick='window.close();' style='cursor:pointer' title=' "+size+" \n\n 클릭하면 닫혀요. '"; 
    win.document.write ("<style>.dragme{position:relative;}</style> \n"); 
    win.document.write ("</head> \n\n"); 
    win.document.write ("<body leftmargin=0 topmargin=0 bgcolor=#dddddd style='cursor:arrow;'> \n"); 
    win.document.write ("<table width=100% height=100% cellpadding=0 cellspacing=0><tr><td align=center valign=middle><img src='"+img.src+"' width='"+w+"' height='"+h+"' border=0 class='dragme' "+click+"></td></tr></table>");
    win.document.write ("</body></html>"); 
    win.document.close(); 

    if(parseInt(navigator.appVersion) >= 4){win.window.focus();} 
}

function numberFormat(val) {
	val = ''+val; // val을 string으로 강제형변환
	if(val.length<=3) return val;

	var os = val.length%3;
	if(os==0) os=3;
	return val.substring(0,os)+','+numberFormat(val.substring(os));
}

function chk_value(obj, str) {

	if (isNull(obj.value)) {
		alert(str);
		obj.focus();
		return false;
	} else {
		return true;
	}
}

function containsCharsOnlyID(input,chars) {

	for (var inx = 0; inx < input.value.length; inx++) {
		if (chars.indexOf(input.value.charAt(inx)) == -1) {

			alert("영문자, 숫자, _만 입력 가능합니다..");

			input.value = input.value.replace(input.value.charAt(inx), '');
			//alert(input.value.charAt(inx));
			//input.value = input.value.substring(0,input.value.length -1);
			
			input.focus();
			return false;
		}
	}
	return true;

}

function containsCharsOnly(input,chars) {
	return containsCharsOnly2(input,chars,chars,true);
}

function containsCharsOnly2(input,chars,al,flag) {
	
	for (var inx = 0; inx < input.value.length; inx++) {
		if (chars.indexOf(input.value.charAt(inx)) == -1) {

			if(flag)alert(al + "만 입력 가능합니다..");

			input.value = input.value.replace(input.value.charAt(inx), '');

			input.focus();
			return false;
		}
	}
	return true;	
}

// 전화번호 검사 
//
function isPhoneNumber(input) {
	var chars = "1234567890-~() ";
	return containsCharsOnly(input,chars);
}

function isNumber(input) {
	var chars = "1234567890";
	return containsCharsOnly(input,chars);
}
function isNumberReal(input) {
	var chars = "1234567890.-+";
	return containsCharsOnly(input,chars);
}

function isScaleNumber(input) {
	var chars = "1234567890X";
	return containsCharsOnly(input,chars);
}

function isAlphabetNumber(input) {
	var chars = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	return containsCharsOnly2(input,chars,"",false);
}

function isAlphabet_Number(input) {
	var chars = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
	return containsCharsOnlyID(input,chars);
}

function isEmail(str){
	var objEv = str;
			msg=false;
	if(objEv.length != 0){
		if (objEv.search(/^\s*[\w\~\-\.]+\@[\w\~\-]+(\.[\w\~\-]+)+\s*$/g)>=0){
			msg=true;
		}else {
			//alert("E-Mail을 확인하세요.");
			msg=false;
		}
	}
	return msg;
}

function js_login() {

	var frm = document.frm_login;

	if (frm.m_id.value.trim() == "") {
		 alert("아이디를 입력해주십시오.");
		frm.m_id.focus();
		return false;
	}

	if (frm.m_password.value.trim() == "") {
		 alert("비밀번호를 입력해주십시오.");
		frm.m_password.focus();
		return false;
	}
	
	frm.mode.value = "LOGIN";
	frm.action = "../member/login.check.php"
	return true;
}

function isIE() {
	var name = navigator.appName;
	if (name == "Microsoft Internet Explorer")
		return true;
	else
		return false;
}

function js_next_obj(chklen, chk_obj, next_obj) {
	if ($("#"+chk_obj).val().length >= parseInt(chklen)) {
		$("#"+next_obj).focus();
	}
}

function dateDiff(_date1, _date2) {
	
	var diffDate_1 = _date1 instanceof Date ? _date1 : new Date(_date1);
	var diffDate_2 = _date2 instanceof Date ? _date2 : new Date(_date2);

	diffDate_1 = new Date(diffDate_1.getFullYear(), diffDate_1.getMonth()+1, diffDate_1.getDate());
	diffDate_2 = new Date(diffDate_2.getFullYear(), diffDate_2.getMonth()+1, diffDate_2.getDate());

	var diff = Math.abs(diffDate_2.getTime() - diffDate_1.getTime());
	diff = Math.ceil(diff / (1000 * 3600 * 24));
	return diff;
}

$(document).on("keyup", ".onlynum", function() {
	$(this).val($(this).val().replace(/[^0-9]/g,''));
});

$(document).on("keyup", ".onlyphone", function() {
	$(this).val($(this).val().replace(/[^0-9-]/g,''));
});

$(document).on("keyup", ".onlynumAlphabet", function() {
	$(this).val($(this).val().replace(/[^0-9a-zA-Z]/g,''));
});


$(document).on("keyup", ".onlynumAlphabetSpecial", function() {
	$(this).val($(this).val().replace(/[^0-9a-zA-Z~!@#$%^&*()_+|<>?:{}]/g,''));
});


$(document).on("keyup focus", ".inputphone", function() {

	var sMsg = $(this).val(); 
	var onlynum = "" ; 
	onlynum = RemoveDash2(sMsg);
	onlynum =  checkDigit(onlynum);

	if(event.keyCode != 12 ) { 
		if(onlynum.substring(0,2) == 02) {  // 서울전화번호일 경우  10자리까지만 나타나교 그 이상의 자리수는 자동삭제 
			if (GetMsgLen(onlynum) <= 1) $(this).val(onlynum); 
			if (GetMsgLen(onlynum) == 2) $(this).val(onlynum + "-"); 
			if (GetMsgLen(onlynum) == 4) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,3)); 
			if (GetMsgLen(onlynum) == 4) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,4)); 
			if (GetMsgLen(onlynum) == 5) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,5)); 
			if (GetMsgLen(onlynum) == 6) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,6)); 
			if (GetMsgLen(onlynum) == 7) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,5) + "-" + onlynum.substring(5,7));
			if (GetMsgLen(onlynum) == 8) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,8)); 
			if (GetMsgLen(onlynum) == 9) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,5) + "-" + onlynum.substring(5,9)); 
			if (GetMsgLen(onlynum) == 10) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,10)); 
			if (GetMsgLen(onlynum) == 11) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,10)); 
			if (GetMsgLen(onlynum) == 12) $(this).val(onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,10)); 
		}
		
		if(onlynum.substring(0,2) == 05 ) {  // 05로 시작되는 번호 체크 
			if(onlynum.substring(2,3) == 0 ) {  // 050으로 시작되는지 따지기 위한 조건문 
				if (GetMsgLen(onlynum) <= 3) $(this).val(onlynum); 
				if (GetMsgLen(onlynum) == 4) $(this).val(onlynum + "-"); 
				if (GetMsgLen(onlynum) == 5) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,5)); 
				if (GetMsgLen(onlynum) == 6) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,6)); 
				if (GetMsgLen(onlynum) == 7) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,7)); 
				if (GetMsgLen(onlynum) == 8) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8)); 
				if (GetMsgLen(onlynum) == 9) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,7) + "-" + onlynum.substring(7,9)); 
				if (GetMsgLen(onlynum) == 10) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8) + "-" + onlynum.substring(8,10)); 
				if (GetMsgLen(onlynum) == 11) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,7) + "-" + onlynum.substring(7,11)); 
				if (GetMsgLen(onlynum) == 12) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8) + "-" + onlynum.substring(8,12)); 
				if (GetMsgLen(onlynum) == 13) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8) + "-" + onlynum.substring(8,12)); 
			} else { 
				if (GetMsgLen(onlynum) <= 2) $(this).val(onlynum);
				if (GetMsgLen(onlynum) == 3) $(this).val(onlynum + "-"); 
				if (GetMsgLen(onlynum) == 4) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,4)); 
				if (GetMsgLen(onlynum) == 5) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,5)); 
				if (GetMsgLen(onlynum) == 6) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6)); 
				if (GetMsgLen(onlynum) == 7) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7)); 
				if (GetMsgLen(onlynum) == 8) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,8));
				if (GetMsgLen(onlynum) == 9) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,9)); 
				if (GetMsgLen(onlynum) == 10) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,10)); 
				if (GetMsgLen(onlynum) == 11) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11)); 
				if (GetMsgLen(onlynum) == 12) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11)); 
			} 
		} 

		if(onlynum.substring(0,2) == 03 || onlynum.substring(0,2) == 04  || onlynum.substring(0,2) == 06  || onlynum.substring(0,2) == 07  || onlynum.substring(0,2) == 08 ) {  // 서울전화번호가 아닌 번호일 경우(070,080포함 // 050번호가 문제군요) 
			if (GetMsgLen(onlynum) <= 2) $(this).val(onlynum); 
			if (GetMsgLen(onlynum) == 3) $(this).val(onlynum + "-"); 
			if (GetMsgLen(onlynum) == 4) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,4)); 
			if (GetMsgLen(onlynum) == 5) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,5)); 
			if (GetMsgLen(onlynum) == 6) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6)); 
			if (GetMsgLen(onlynum) == 7) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7)); 
			if (GetMsgLen(onlynum) == 8) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,8));
			if (GetMsgLen(onlynum) == 9) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,9)); 
			if (GetMsgLen(onlynum) == 10) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,10)); 
			if (GetMsgLen(onlynum) == 11) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11)); 
			if (GetMsgLen(onlynum) == 12) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11)); 
		} 

		if(onlynum.substring(0,2) == 01) {  //휴대폰일 경우 
			if (GetMsgLen(onlynum) <= 2) $(this).val(onlynum); 
			if (GetMsgLen(onlynum) == 3) $(this).val(onlynum + "-"); 
			if (GetMsgLen(onlynum) == 4) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,4)); 
			if (GetMsgLen(onlynum) == 5) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,5)); 
			if (GetMsgLen(onlynum) == 6) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6)); 
			if (GetMsgLen(onlynum) == 7) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7)); 
			if (GetMsgLen(onlynum) == 8) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,8)); 
			if (GetMsgLen(onlynum) == 9) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,9)); 
			if (GetMsgLen(onlynum) == 10) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,10)); 
			if (GetMsgLen(onlynum) == 11) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11)); 
			if (GetMsgLen(onlynum) == 12) $(this).val(onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11)); 
		} 

		if(onlynum.substring(0,1) == 1) {  // 1588, 1688등의 번호일 경우 
			if (GetMsgLen(onlynum) <= 3) $(this).val(onlynum); 
			if (GetMsgLen(onlynum) == 4) $(this).val(onlynum + "-"); 
			if (GetMsgLen(onlynum) == 5) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,5)); 
			if (GetMsgLen(onlynum) == 6) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,6)); 
			if (GetMsgLen(onlynum) == 7) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,7)); 
			if (GetMsgLen(onlynum) == 8) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8)); 
			if (GetMsgLen(onlynum) == 9) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8)); 
			if (GetMsgLen(onlynum) == 10) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8)); 
			if (GetMsgLen(onlynum) == 11) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8)); 
			if (GetMsgLen(onlynum) == 12) $(this).val(onlynum.substring(0,4) + "-" + onlynum.substring(4,8)); 
		} 
	} 

});


function right(value, count){
	var return_value = "";
	return_value = value.substring((value.length - count), value.length);
	return return_value;
}

function left(value, count){
	var return_value = "";
	return_value = value.substring(0, count);
	return return_value;
}

/*
function OnCheckDate(oTa) { 

	var oForm = oTa.form ; 
	var sMsg = oTa.value ; 
	var onlynum = "" ; 
	var imsi=0; 
	onlynum = RemoveDash2(sMsg);
	onlynum =  checkDigit(onlynum);
	var retValue = ""; 
	
	if(event.keyCode != 12 ) {
		if (GetMsgLen(onlynum) <= 3) oTa.value = onlynum ; 
		if (GetMsgLen(onlynum) == 4) oTa.value = onlynum + "-"; 
		if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,5) ; 
		if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) ; 
		if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,7) ;
		if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,8) ; 
	}
}
*/
function OnCheckDate(oTa) { 

	var oForm = oTa.form ; 
	var sMsg = oTa.value ; 
	var onlynum = "" ; 
	var imsi=0; 
	onlynum = RemoveDash2(sMsg);
	onlynum = checkDigit(onlynum);
	var retValue = ""; 

	if (event.keyCode != 12) {
		if (GetMsgLen(onlynum) <= 3) oTa.value = onlynum ; 
		if (GetMsgLen(onlynum) == 4) oTa.value = onlynum + "-"; 

		//if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,5) ; 
		if (GetMsgLen(onlynum) == 5) {
			if (onlynum.substring(4,5) > 1) {
				oTa.value = onlynum.substring(0,4) + "-0"+ onlynum.substring(4,5); 
			} else {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,5); 
			}
		}

		//if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) ; 
		if (GetMsgLen(onlynum) == 6) {
			if (onlynum.substring(4,6) > 12) {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,5); 
			} else {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6);
			}
		}

		//if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,7);
		if (GetMsgLen(onlynum) == 7) {
			if (onlynum.substring(6,7) > 3) {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) +"-0"+ onlynum.substring(6,7); 
			} else {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,7)
			}
		}
		
		//if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,8);
		if (GetMsgLen(onlynum) == 8) {
			if (onlynum.substring(6,8) > 31) {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,7);
			} else {
				oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) + "-" + onlynum.substring(6,8);
				
			}
		}

	}
}

function OnCheckPhone(oTa) { 

	var oForm = oTa.form ; 
	var sMsg = oTa.value ; 
	var onlynum = "" ; 
	var imsi=0; 
	onlynum = RemoveDash2(sMsg);  //하이픈 입력시 자동으로 삭제함 
	onlynum =  checkDigit(onlynum);  // 숫자만 입력받게 함 
	var retValue = ""; 

	if(event.keyCode != 12 ) { 
		if(onlynum.substring(0,2) == 02) {  // 서울전화번호일 경우  10자리까지만 나타나교 그 이상의 자리수는 자동삭제 
			if (GetMsgLen(onlynum) <= 1) oTa.value = onlynum ; 
			if (GetMsgLen(onlynum) == 2) oTa.value = onlynum + "-"; 
			if (GetMsgLen(onlynum) == 4) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,3) ; 
			if (GetMsgLen(onlynum) == 4) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,4) ; 
			if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,5) ; 
			if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,6) ; 
			if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,5) + "-" + onlynum.substring(5,7) ;
			if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,8) ; 
			if (GetMsgLen(onlynum) == 9) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,5) + "-" + onlynum.substring(5,9) ; 
			if (GetMsgLen(onlynum) == 10) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,10) ; 
			if (GetMsgLen(onlynum) == 11) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,10) ; 
			if (GetMsgLen(onlynum) == 12) oTa.value = onlynum.substring(0,2) + "-" + onlynum.substring(2,6) + "-" + onlynum.substring(6,10) ; 
		}
		
		if(onlynum.substring(0,2) == 05 ) {  // 05로 시작되는 번호 체크 
			if(onlynum.substring(2,3) == 0 ) {  // 050으로 시작되는지 따지기 위한 조건문 
				if (GetMsgLen(onlynum) <= 3) oTa.value = onlynum ; 
				if (GetMsgLen(onlynum) == 4) oTa.value = onlynum + "-"; 
				if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,5) ; 
				if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) ; 
				if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,7) ; 
				if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) ; 
				if (GetMsgLen(onlynum) == 9) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,7) + "-" + onlynum.substring(7,9) ; ; 
				if (GetMsgLen(onlynum) == 10) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) + "-" + onlynum.substring(8,10) ; 
				if (GetMsgLen(onlynum) == 11) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,7) + "-" + onlynum.substring(7,11) ; 
				if (GetMsgLen(onlynum) == 12) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) + "-" + onlynum.substring(8,12) ; 
				if (GetMsgLen(onlynum) == 13) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) + "-" + onlynum.substring(8,12) ; 
			} else { 
				if (GetMsgLen(onlynum) <= 2) oTa.value = onlynum ; 
				if (GetMsgLen(onlynum) == 3) oTa.value = onlynum + "-"; 
				if (GetMsgLen(onlynum) == 4) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,4) ; 
				if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,5) ; 
				if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) ; 
				if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) ; 
				if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,8) ;
				if (GetMsgLen(onlynum) == 9) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,9) ; 
				if (GetMsgLen(onlynum) == 10) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,10) ; 
				if (GetMsgLen(onlynum) == 11) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11) ; 
				if (GetMsgLen(onlynum) == 12) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11) ; 
			} 
		} 

		if(onlynum.substring(0,2) == 03 || onlynum.substring(0,2) == 04  || onlynum.substring(0,2) == 06  || onlynum.substring(0,2) == 07  || onlynum.substring(0,2) == 08 ) {  // 서울전화번호가 아닌 번호일 경우(070,080포함 // 050번호가 문제군요) 
			if (GetMsgLen(onlynum) <= 2) oTa.value = onlynum ; 
			if (GetMsgLen(onlynum) == 3) oTa.value = onlynum + "-"; 
			if (GetMsgLen(onlynum) == 4) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,4) ; 
			if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,5) ; 
			if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) ; 
			if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) ; 
			if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,8) ;
			if (GetMsgLen(onlynum) == 9) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,9) ; 
			if (GetMsgLen(onlynum) == 10) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,10) ; 
			if (GetMsgLen(onlynum) == 11) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11) ; 
			if (GetMsgLen(onlynum) == 12) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11) ; 
		} 

		if(onlynum.substring(0,2) == 01) {  //휴대폰일 경우 
			if (GetMsgLen(onlynum) <= 2) oTa.value = onlynum ; 
			if (GetMsgLen(onlynum) == 3) oTa.value = onlynum + "-"; 
			if (GetMsgLen(onlynum) == 4) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,4) ; 
			if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,5) ; 
			if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) ; 
			if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) ; 
			if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,8) ; 
			if (GetMsgLen(onlynum) == 9) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,9) ; 
			if (GetMsgLen(onlynum) == 10) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,6) + "-" + onlynum.substring(6,10) ; 
			if (GetMsgLen(onlynum) == 11) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11) ; 
			if (GetMsgLen(onlynum) == 12) oTa.value = onlynum.substring(0,3) + "-" + onlynum.substring(3,7) + "-" + onlynum.substring(7,11) ; 
		} 

		if(onlynum.substring(0,1) == 1) {  // 1588, 1688등의 번호일 경우 
			if (GetMsgLen(onlynum) <= 3) oTa.value = onlynum ; 
			if (GetMsgLen(onlynum) == 4) oTa.value = onlynum + "-"; 
			if (GetMsgLen(onlynum) == 5) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,5) ; 
			if (GetMsgLen(onlynum) == 6) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,6) ; 
			if (GetMsgLen(onlynum) == 7) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,7) ; 
			if (GetMsgLen(onlynum) == 8) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) ; 
			if (GetMsgLen(onlynum) == 9) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) ; 
			if (GetMsgLen(onlynum) == 10) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) ; 
			if (GetMsgLen(onlynum) == 11) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) ; 
			if (GetMsgLen(onlynum) == 12) oTa.value = onlynum.substring(0,4) + "-" + onlynum.substring(4,8) ; 
		} 
	} 
} 

function OnCheckHour(oTa) { 

	var oForm = oTa.form ; 
	var sMsg = oTa.value ; 
	var onlynum = "" ; 
	var imsi=0; 
	onlynum = RemoveDash2(sMsg);
	onlynum = checkDigit(onlynum);
	var retValue = ""; 

	if (event.keyCode != 12) {
		if (GetMsgLen(onlynum) == 1) {
			if (onlynum.substring(0,1) > 2) {
				oTa.value = "0"+onlynum.substring(0,1);
			} else {
				oTa.value = onlynum.substring(0,1);
			}
		}

		if (GetMsgLen(onlynum) == 2) { 
			if (onlynum.substring(0,2) > 24) {
				oTa.value = onlynum.substring(0,1);
			} else {
				oTa.value = onlynum.substring(0,2);
			}
		}
	}
}

function OnCheckMin(oTa) { 

	var oForm = oTa.form ; 
	var sMsg = oTa.value ; 
	var onlynum = "" ; 
	var imsi=0; 
	onlynum = RemoveDash2(sMsg);
	onlynum = checkDigit(onlynum);
	var retValue = ""; 

	if (event.keyCode != 12) {
		if (GetMsgLen(onlynum) == 1) {
			if (onlynum.substring(0,1) > 5) {
				oTa.value = "0"+onlynum.substring(0,1);
			} else {
				oTa.value = onlynum.substring(0,1);
			}
		}

		if (GetMsgLen(onlynum) == 2) { 
			if (onlynum.substring(0,2) > 59) {
				oTa.value = onlynum.substring(0,1);
			} else {
				oTa.value = onlynum.substring(0,2);
			}
		}
	}
}


function RemoveDash2(sNo) { 
	var reNo = "" 
	for(var i=0; i<sNo.length; i++) { 
		if ( sNo.charAt(i) != "-" ) { 
			reNo += sNo.charAt(i) 
		} 
	} 
	return reNo 
} 

function GetMsgLen(sMsg) { // 0-127 1byte, 128~ 2byte 
	var count = 0 
	for(var i=0; i<sMsg.length; i++) { 
		if ( sMsg.charCodeAt(i) > 127 ) { 
			count += 2 
		} else { 
			count++ 
		} 
	} 
	return count 
} 

function checkDigit(num) { 
	var Digit = "1234567890"; 
	var string = num; 
	var len = string.length 
	var retVal = ""; 

	for (i = 0; i < len; i++) { 
		if (Digit.indexOf(string.substring(i, i+1)) >= 0) { 
			retVal = retVal + string.substring(i, i+1); 
		} 
	} 
	return retVal; 
} 

function isDate(str) {
	if ( !/([0-9]{4})-([0-9]{2})-([0-9]{2})/.test(str) )  {
		alert("날짜의 형식이 잘못 입력되었습니다.\n예) 1996-04-05");
		return false;
	}
	
	// 현재 날짜
	var toDay = new Date();
	// 입력된 날짜 배열 처리
	var arrDate = str.split('-');
	// 입력된 날짜의 마지막 일자
	var maxDay = new Date(new Date(arrDate[0], arrDate[1], 1) - 86400000).getDate();
	
	if  ( arrDate[0] == 0000 || arrDate[0] > toDay.getFullYear() )  {
		alert("잘못된 년도를 입력하였습니다.");
		return false;
	}
	if  ( arrDate[1] == 00 || arrDate[1] > 12  )  {
		alert("잘못된 월을 입력하였습니다.");
		return false;
	}
	if  ( arrDate[2] == 00 || arrDate[2] > maxDay )  {
		alert("잘못된 일을 입력하였습니다.");
		return false;
	}
	return true;
}

var entityMapIn = { '"': '##쌍따움표##', "'": '##따움표##'};
var entityMapOut = {'##쌍따움표##': '"', "##따움표##": "'"};

function convertHtml (string) { 
	return String(string).replace(/[&<>"'`=\/]/g, function (s) { 
		return entityMapIn[s]; 
	}); 
}


function reverseHtml (string) { 
	return String(string).replace(/[&<>"'`=\/]/g, function (s) { 
		return entityMapOut[s]; 
	}); 
}

// 전체 선택 
$(document).on("click","#all_chk_no",function() {
	if ($(this).is(":checked") == true) {
		$("."+$(this).attr("alt")).prop("checked",true);
	} else {
		$("."+$(this).attr("alt")).prop("checked",false);
	}
});