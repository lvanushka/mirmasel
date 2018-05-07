<?php
session_start();
require_once './inc/config.inc';
require_once './inc/rootPanels.php';
echo $ROPanel;
if (isset($_SESSION['userRole'])&&($_SESSION['userRole'])<=1){
if (isset($_REQUEST['create'])){//Создание нового товара
   $conn=Con();
   $sql="insert into heap(PartN,ProdName,mes,price,Contraid) values('000','Наименование','шт',99,7)";
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->execute();
   $res=$stmt->store_result();
  // $j=$stmt->affected_rows; 
   $newItemId=$conn->insert_id;
    $_SESSION['createItemHID']=$newItemId;
   $sql="insert into rootcart(hid,prodName,cid,vol,price,priceOut,bonus,uid)"
   . " values($newItemId,'Наименование',7,1,100,130,10,$_SESSION[userID])";
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->execute();
   $res=$stmt->store_result();
   $rootCartItemId=$conn->insert_id;
   echo "<script>editRootCartItem($rootCartItemId)</script>";
   die();
}
if (isset($_REQUEST['itemID'])){$itemID=$_REQUEST['itemID'];} else die("нет идентификатора товара");
$_SESSION['location']=array(0=>"itemEditor",1=>$_REQUEST["itemID"]);
//echo "location is ".$_SESSION['location'][0];
if (!isset($_REQUEST['saveChanges'])){
    
$sql="select rootcart.id,hid,rootcart.ProdName,"
."rootcart.price,rootcart.priceOut,bonus,rootcart.vol,rootcart.step"
." from rootcart,heap where (heap.id=rootcart.hid)"
." and rootcart.id=?";
   $conn=Con();
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->bind_param(i, $itemID);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   if (j===0) die("Something was wrong on your way here");
   $stmt->bind_result($cart['id'],$cart['hid'],$cart['prodname'],
                      $cart['price'],$cart['priceOut'],$cart['bonus'],$cart['vol'],$cart['step']);
   echo ('<table class="item"');
  $stmt->fetch();
  unset($int,$float,$filters,$chem);
  //if ($cart['step']==='i') else $float="checked";
  switch ($cart['step']){
      case 'i': $int="checked";break;
      case 'f': $float="checked";break;
      case 'A': $filters="checked";break;
      case 'x': $chem="checked";break;
  }
       echo  "<tr><td># в куче</td><td>$cart[hid]</td></tr>".
             "<tr><td>Наименование</td><td><input type='text' style='width:100%;background:rgba(0,0,0,0)' id='prodName' value='$cart[prodname]'/></td></tr>".
             "<tr><td>Цена закуп</td><td><input type='number' id='priceIn' value='$cart[price]'/></td></tr>".
             "<tr><td>Цена реал</td><td><input type='number' id='priceOut' value='$cart[priceOut]'/></td></tr>".
             "<tr><td>Bonus</td><td><input type='number' id='bonus' value='$cart[bonus]'/></td></tr>".
             "<tr><td>Количество</td>".
	"<td><button id='voldec' onclick='if($(\"#vol\").val()*1>1) $(\"#vol\").val($(\"#vol\").val()-1)'>-</button>".
	"<input type='number' id='vol' min=\"1\" step=\"1\" value=$cart[vol] />".
	"<button id='volinc' onclick='$(\"#vol\").val($(\"#vol\").val()-(-1))'>+</button></td></tr>".
           "<tr><td>Тип</td><td>"
            . "<input id='step' name='mes' type='radio' value='i' style='font-size:120%' $int>Фасовка</input>"
            . "<input id='step' name='mes' type='radio' value='f' $float>Розлив</input>"
            . "<input id='step' name='mes' type='radio' value='A' $filters>Фильтра</input>"
        . "<input id='step' name='mes' type='radio' value='x' $chem >Химия/Тосол</input></td></tr>";
                   //"<tr><td>Категория</td><td><select name='cats' id='cats'><option disabled selected >Категория";fillCats();echo "</select>".
                  //"<select name='scats' id='scats'><option disabled selected >Подкатегория</select>";
echo "<tr><td><button onclick='editRootCartItem($cart[id])'>Сброс</button></td>"
   . "<td><button onclick='saveRootCartItem($cart[id])'>Сохранить</button></td></tr></table>";
   } else {
if ($_SESSION["userRole"]==0){
    $prodName=$_REQUEST['prodName'];
    $priceIn=$_REQUEST['priceIn'];
    $priceOut=$_REQUEST['priceOut'];
    $volume=$_REQUEST['volume'];
    $bonus=$_REQUEST['bonus'];
    $step=$_REQUEST['step'];
    $sql="update rootcart set prodName=?, price=?, priceOut=?, bonus=?, vol=?, step=? where id=?";
    $conn=Con();
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->bind_param(siiiisi,$prodName,$priceIn,$priceOut,$bonus,$volume,$step,$itemID);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   if ($j==0) die("Ошибка сохранения.Проверте ввод $j"); else echo "Сохранено успешно $j";
   }
if (isset($_SESSION['createItemHID'])){//если создана новая позиция в куче,применяем изменения к этой позиции тоже
   $sql="update heap set ProdName=?, price=? where id=?";
    $conn=Con();
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->bind_param('sii',$prodName,$priceIn,$_SESSION['createItemHID']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
    if ($j==0) die("Ошибка сохранения.Проверте ввод $j"); else echo "Сохранено успешно $j";
   unset($_SESSION['createItemHID']);
    }
}
} else    die ('<h2 class="subWindowHeaderN">Недостаточно прав для совершения опреации</h2>');