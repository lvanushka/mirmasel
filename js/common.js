function auth(action){
    if (action=="login")
    var params={userName:document.getElementById("userName").value,
                userPass:document.getElementById("userPass").value};
else var params={action:"logout"};
    var url="php/auth.php";
    $.ajax({url:url,
            type:"post",
            data:params,
            dataType:"json",
            async:"false",
            success:function(data){
        $("#loginForm").fadeOut('slow','swing',function(){$("#loginForm").html(data.grettings);});
        $("#loginForm").fadeIn('slow','swing');
        
        $("#adminPanel").hide('slow','swing',function(){$("#adminPanel").html(data.adminPanel);});
        $("#adminPanel").show('slow','swing');
        
        $("#sellerPanel").hide('slow','swing',function(){$("#sellerPanel").html(data.sellerPanel);});
        $("#sellerPanel").show('slow','swing');
        if (data.sellerPanel!='') Catalog(0);
    }});
if (action=="logout") Catalog(0);
}
function SaveKW(){
    var keyword=$("#Find").val();
    var xmlHttp=new XMLHttpRequest();
    var url="SaveKW.php?keyword="+keyword;
    //alert(keyword);
    xmlHttp.open("GET",url,true);
    xmlHttp.onreadystatechange=function (){if (xmlHttp.readyState<4){return;} else console.log('saveKW:'+xmlHttp.responseText);}
   //alert('saveKW:'+xmlHttp.responseText);
   xmlHttp.send();
}
function Catalog(page=0,keyword="",sort="naim"){
    //new XMLHttpRequest().open("GET","php/SaveKW.php?keyword="+$("#Find").val(),false);
  //  $("#userPass").unbind("keypress");
  //  $("#userPass").bind("keypress",function(e){if ((e.keyCode===13)&&($("#userPass").val().length>3)) auth('login');});
    $("#Find").unbind("keypress");
    $("#Find").bind("keypress",function(e){if (e.keyCode===13){SaveKW();Catalog(0,$("#Find").val());};});//||((e.keyCode===0))&&($("#Find").val().length>3)
    var url="catalog.php?catPage="+page;//} else {return;}
   // if (typeof(keyword)!=='undefined'){
        url+="&keyword="+keyword;
   // }
   // if (typeof(sort)!=='undefined'){
        url+="&sort="+sort;
    //}
    var xmlHttp=new XMLHttpRequest();
        //fireOnChangeEvent();
    xmlHttp.open("GET",url,true);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {
            $("#mystyle").fadeOut('fast','swing',function(){$("#mystyle").html(xmlHttp.responseText);});
            $("#mystyle").fadeIn('fast','swing',function(){/*window.scrollTo(0,400); Jr maybee something usefull --DrLin*/});
      } 
              
  };
      xmlHttp.send();
        }
       

function Order(arg){
    $()
    if (arg=='delete') {var url="order.php?delete=1";}
    else 
    if (arg=='ordersList') {var url="order.php?ordersList=1";}
    else 
    if (arg=='showCart')   {var url="order.php?showCart=1";}
    else 
    if (arg=='realise')    {var url="order.php?realise=1";}
    else 
    if (arg=='payForOrder'){var url="order.php?payForOrder=1";}
    else 
    if (arg=='printDoc')   {var url="order.php?printDoc=1";}
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;} 
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
        }
           
function Users(){
    var url="userMan.php";
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");
   }
      };
      xmlHttp.send();
        }
        
function News(){
    var url="News.php";
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
        }

function OrderDetails(orderID){
    var url="order.php?showDetails="+orderID;
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
        }
        
function Item(id){
    var url="Item.php?itemID="+id;
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
        }
function Print(){
  var divToPrint=$("#mystyle").html();
  var newDoc=window.open('','','left=50,top=50,width=720,height=480,scrollbars=1,toolbar=1,statusbar=1');
newDoc.document.writeln('<!DOCTYPE html><html><head><link rel="stylesheet" href="style/print.css" type="text/css" media="all" /></head><body><div id="Chek">');
newDoc.document.writeln(divToPrint);
  newDoc.document.writeln('</div></body></html>');
  var btn=newDoc.document.getElementById('printBtn');
  btn.remove();
  newDoc.document.close();
  newDoc.focus();
  newDoc.print();
        }
function setOrdersPage(page){
    url="order.php?ordersList=1&ordersPage="+page;
 var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
        
}

function TPSelector(tpid){
    url="inc/sessions.php?tpid="+tpid;
 var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
        }
 function goodBack(prodflowId){
    // alert('В разработке 02.08.2017');
    var yes=confirm("Принять этот товар от покупателя?");
    if (yes){
   url="order.php?goodBack="+prodflowId;
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
           }
           else alert('Возврат товара О Т М Е Н Ё Н');
 }
function printList(tradePoint){
    prodList=window.open('http://mirmasel.xyz/print.php?tradePoint='+tradePoint,'','');
    //prodList.print();
    
}
function ChillTrax(){
    window.open('https://www.chilltrax.com/playlist/player.html','mywindow','scrollbars=no,resizable=no,location=no,menubar=no,status=yes,toolbar=no,left='+(screen.availWidth/2-275)+',top='+(screen.availHeight/2-72.5)+',width=560,height=160');
}
function disableControl(sender){
    $(sender).replaceWith('<td style="text-align:center;margin:0;padding:0;"><img src="/img/wait3.gif" alt=":)"/></td>');
   
}
function selectOrders(sender){
    alert($("dp").data('datepicker'));
}
 function checkCart(){
alert("checkCart()");
   url="order.php?checkCart=true";
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(xmlHttp.responseText);});
        $("#mystyle").fadeToggle("slow","swing");}
      };
      xmlHttp.send();
 }
 
 function qsChangeOrder(){
      url="Filter.php?qsOrder="+$("input[name=qsOrder]:checked").val();
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {$("#Find").val()=='';}
      };
      xmlHttp.send();
 }