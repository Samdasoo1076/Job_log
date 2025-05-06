var nowZoom = 100; // 현재비율
var maxZoom = 200; // 최대비율(500으로하면 5배 커진다)
var minZoom = 100; // 최소비율

var uAgent = navigator.userAgent.toLowerCase();
var mobilePhones = new Array('firefox');
var fox="N";
for(var i=0;i<mobilePhones.length;i++){
	if(uAgent.indexOf(mobilePhones[i]) != -1) {
		fox="Y";
	}
}

//화면 키운다.
function zoomIn()
{
	if(fox=="Y"){
		alert('이 브라우저는 해당 기능을 지원하지 않습니다.');
		return;
	}
    if (nowZoom < maxZoom)
    {
        nowZoom += 10; //25%씩 커진다.
    }
    else
    {
        return;
    }
    document.body.style.zoom = nowZoom + "%";
}


//화면 줄인다.
function zoomOut()
{

	if(fox=="Y"){
		alert('이 브라우저는 해당 기능을 지원하지 않습니다.');
		return;
	}

    if (nowZoom > minZoom)
    {
        nowZoom -= 10; //25%씩 작아진다.
    }
    else
    {
        return;
    }
    document.body.style.zoom = nowZoom + "%";
}

//화면 원래대로
function zoomDefault()
{
    nowZoom = 100;
	fSize = 12;
    document.body.style.zoom = nowZoom + "%";
	var objTd = document.getElementsByTagName( "td" );// <td>, <TD>의 내용을 모두 읽어옴
	var objDiv = document.getElementsByTagName( "div" );
	var objSpan = document.getElementsByTagName( "span" );
	var objA = document.getElementsByTagName( "a" );
	var objs = document.getElementsByTagName( "p" );
	var objli = document.getElementsByTagName( "li" );
/*	for( i=0; i < objDiv.length; i++ )	objDiv[i].style.fontSize = fSize + "px";
	for( i=0; i < objTd.length; i++ )	objTd[i].style.fontSize = fSize + "px";
	for( i=0; i < objSpan.length; i++ )	objSpan[i].style.fontSize = fSize + "px";
	for( i=0; i < objA.length; i++ )	objA[i].style.fontSize = fSize + "px";
	for( i=0; i < objs.length; i++ )	objs[i].style.fontSize = fSize + "px";
	for( i=0; i < objli.length; i++ )	objli[i].style.fontSize = fSize + "px";
	*/
}

var fSize = 12;
function scaleFont(n){
	fSize = fSize + n;
	var objTd = document.getElementsByTagName( "td" );// <td>, <TD>의 내용을 모두 읽어옴
	var objDiv = document.getElementsByTagName( "div" );
	var objSpan = document.getElementsByTagName( "span" );
	var objA = document.getElementsByTagName( "a" );
	var objs = document.getElementsByTagName( "p" );
	var objli = document.getElementsByTagName( "li" );
	for( i=0; i < objDiv.length; i++ )	objDiv[i].style.fontSize = fSize + "px";
	for( i=0; i < objTd.length; i++ )	objTd[i].style.fontSize = fSize + "px";
	for( i=0; i < objSpan.length; i++ )	objSpan[i].style.fontSize = fSize + "px";
	for( i=0; i < objA.length; i++ )	objA[i].style.fontSize = fSize + "px";
	for( i=0; i < objs.length; i++ )	objs[i].style.fontSize = fSize + "px";
	for( i=0; i < objli.length; i++ )	objli[i].style.fontSize = fSize + "px";
}

