function Register(){
    var url="register.php";
    var  params={
        newUserName:document.getElementById('login').value,
        newUserEmail:document.getElementById('email').value,
        newUserPass1:document.getElementById('pass1').value,
        newUserPass2:document.getElementById('pass2').value,
        newUserTS:document.getElementById('ts').value,
        newUserOdo:document.getElementById('odo').value,
        newUserOil:document.getElementById('oil').value,
   };
      $.post(url,params,function(data){
          $("#mystyle").fadeToggle("slow","swing",function(){
          $("#mystyle").html(data);});
        $("#mystyle").fadeToggle("slow","swing");
      });
            }
function regForm(){
    var url="regForm.php";
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
function editRootCartItem(itemID){
    var url="editItem.php?itemID="+itemID;
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
function createItem(){
    var url="editItem.php?create=true";
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
        
function saveRootCartItem(itemID){
    var Yes=confirm("Сохранить изменения???");
    if (!Yes) return;
       var prodName=$("#prodName").val();
    var priceIn=$("#priceIn").val();
    var priceOut=$("#priceOut").val();
    var volume=$("#vol").val();
    var bonus=$("#bonus").val();
    var step=$(":radio[name=mes]").filter(":checked").val();
    var url="editItem.php?itemID="+itemID+"&saveChanges="+true+"&prodName="+prodName
    +"&priceIn="+priceIn+"&priceOut="+priceOut+"&bonus="+bonus+"&volume="+volume+"&step="+step;
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {alert(step);rootOrder('showCart');}
      };
      xmlHttp.send();
  
        }

function selectTP(tradepointId){
    var url="rootOrder.php?realise=1&TP="+tradepointId;
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
function PrintCRO(){
    prodList=window.open('http://mirmasel.xyz/printCRO.php','','');
}

function cashFlow(){
    var url="cashflow.php?showCash=true";
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
//Изъятие наличности
function takeCash(tpid){
    var cash=Number(prompt('Введите сумму, Доступно:',0));
    if (isNaN(cash)||cash<=0) {alert("Ошибка ввода.Проверьте значение");return;}
    var url="cashflow.php?takeCash="+cash+"&tpid="+tpid;
    var xmlHttp=new XMLHttpRequest();
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {TPSelector(tpid);
      }
      }; 
      xmlHttp.send();
        }