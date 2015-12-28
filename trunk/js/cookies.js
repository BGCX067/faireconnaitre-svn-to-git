function setCookie(c_name,value,expiredays) 
{ 
var exdate=new Date(); 
exdate.setDate(exdate.getDate()+expiredays); 
document.cookie=c_name+ "=" +escape(value)+ 
((expiredays==null) ? "" : ";expires="+exdate.toGMTString()); 

if (document.cookie.length<=0) 
    location="jscookietest.php?cookie=0"; 
} 

function getCookie(c_name) 
{ 
if (document.cookie.length>0) 
  { 
  c_start=document.cookie.indexOf(c_name + "="); 
  if (c_start!=-1) 
    { 
    c_start=c_start + c_name.length+1; 
    c_end=document.cookie.indexOf(";",c_start); 
    if (c_end==-1) c_end=document.cookie.length; 
    return unescape(document.cookie.substring(c_start,c_end)); 
    } 
  } 
return ""; 
} 

function issetCookie(c_name) 
{ 
value=getCookie(c_name); 
if (value!=null && value!="") 
{ 
    return 1; 
} 
else 
{ 
    return 0; 
} 
} 

function deleteCookie ( cookie_name ) 
{ 
  var cookie_date = new Date ( );  // current date & time 
  cookie_date.setTime ( cookie_date.getTime() - 1 ); 
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString(); 
}