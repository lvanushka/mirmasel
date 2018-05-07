function ProdMaster(page,contra,keyword){
    $("#Find").unbind("keypress");
    $("#Find").bind("keypress",function(e){if (e.keyCode===13){SaveKW(); ProdMaster(0,contra,$("#Find").val());};});
    if (typeof(page)!=='undefined'){var url="ProdMaster.php?catPage="+page;} else 
    {var url="ProdMaster.php?catPage=0";}
    if (typeof(keyword)!=='undefined'){url+="&keyword="+keyword;}
     if (typeof(contra)!=='undefined'){url+="&contra="+contra;} 
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,true);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {
          $("#mystyle").fadeOut('fast','swing',function(){$("#mystyle").html(xmlHttp.responseText);});
            $("#mystyle").fadeIn('fast','swing',function(){window.scrollTo(0,400);});
                  } 
  };
      xmlHttp.send();
     // $(document).scrollTop(500);
        }
function addToRootCart(itemId){
    //var maxVal=Number($('#vol_'+itemId).text());
    //alert(maxVal*1111);
    //alert("Sender is: "+#);
    var vol=Number(prompt('Введите количество ',1));
    if (isNaN(vol)||vol<=0) {alert("Ошибка ввода.Проверьте значение");return;}
    var xmlHttp=new XMLHttpRequest();
    var url="Cart.php?itemID="+itemId+"&volume="+vol;
    xmlHttp.open("GET",url,true);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {
       if (xmlHttp.responseText=false) alert('Выполните вход ');
   }
      };
      xmlHttp.send();
}
function deleteFromRootCart(itemId){
    var del=confirm("Удалить??");
    if (del){
    var xmlHttp=new XMLHttpRequest();
    var url="Cart.php?deleteID="+itemId;
    xmlHttp.open("GET",url,true);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {
            if (xmlHttp.responseText=false) alert('Выполните вход ');
   }
      };
      xmlHttp.send();}
  else return;
}
function rootOrder(arg){
    if (arg=='ordersList') {var url="rootOrder.php?ordersList=1";}
    else 
  if (arg[0]=='ordersList'&&(typeof(arg[1])!=='undefined')) {var url="rootOrder.php?ordersList=1&tpid="+arg[1];}        
  else
    if (arg=='showCart')   {var url="rootOrder.php?showCart=1";}
    else 
    if (arg=='selectTP')   {var url="rootOrder.php?selectTP=1";}
    else 
    /*if (arg=='realise')    {var url="rootOrder.php?realise=1";}
    else */
    if (arg=='payForOrder'){var url="rootOrder.php?payForOrder=1";}
    else 
    if (arg=='printDoc')   {var url="rootOrder.php?printDoc=1";}
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
//Детали Заявки
        function rootOrderDetails(orderID){
    var url="rootOrder.php?showDetails="+orderID;
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
//Акции        
        function Promotion(){
    var url="promotion.php";
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
//Печать товарного чека
function printOD(orderId){
var url="printOD.php?orderId="+orderId;
    prodList=window.open(url,'','');
        }
        //Фильтр

function radioCh(){
   var url="Filter.php?filter="+$('input[name="mes"]:checked').val();
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      //else {alert(xmlHttp.responseText);}
  }
      xmlHttp.send();
      Catalog(0);
}
function Balance(tpid){
    var url="cashflow.php?getBalance=true&tpid="+tpid;
    var Bal;
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {Bal=xmlHttp.responseText;}
      }; 
      xmlHttp.send();
      return Bal;
}
function setRootOrdersPage(page){
    url="rootOrder.php?ordersList=1&ordersPage="+page;
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