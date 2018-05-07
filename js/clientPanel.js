function addToCart(itemId){
    var maxVol=Number($('#vol_'+itemId).text());
    var minPrice=Number($('#price_'+itemId).text());
    var vol=Number(prompt('Введите количество Max='+maxVol,1));
    var price=Number(prompt('Введите цену Min='+minPrice,minPrice));
    if (isNaN(vol)||vol<=0) {alert("Ошибка ввода.Проверьте значение");return;}
    if (isNaN(price)||price<minPrice) {alert("Ошибка ввода.Проверьте значение");return;}
//    var maxVal=$('#vol_'+itemId).text();
 //   var vol=prompt('Введите количество, максимум '+maxVal,1);
  //  if ((isNaN(vol))||(vol>maxVal)||(vol<=0)) {alert("Введено недопустимое значение");return;}
    var xmlHttp=new XMLHttpRequest();
    var url="Cart.php?itemID="+itemId+"&volume="+vol+"&price="+price;
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {
       if (xmlHttp.responseText=false) alert('Выполните вход ');
   }
      };
      xmlHttp.send();
}

function deleteFromCart(itemId){
    var del=confirm("Удалить??");
    if (del){
    var xmlHttp=new XMLHttpRequest();
    var url="Cart.php?deleteID="+itemId;
    xmlHttp.open("GET",url,false);
    xmlHttp.onreadystatechange=function (){
      if (xmlHttp.readyState<4) {return;}
      else {
          Order('showCart');
       if (xmlHttp.responseText=false) alert('Выполните вход ');
   }
      };
      xmlHttp.send();}
  else return;
}
