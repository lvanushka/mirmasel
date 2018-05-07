<?php session_start();
require_once './inc/config.inc';
require_once './inc/rootPanels.php';
echo $ROPanel;
if (isset($_SESSION['userRole']) and ($_SESSION['userRole']<=1))
{
    $_SESSION["location"]="Contra";
$conn=Con();
if (!isset($_REQUEST['contra'])){
    $sql='select id,name from contra';
    $stmt=$conn->prepare($sql);
    $stmt->bind_result($contra['id'],$contra['name']);
   $stmt->execute();
   $stmt->store_result();
   echo "<table class='catalog'><th>#</th><th>Поставщик</th>"
   . "<tr onclick='ProdMaster(0,0)' style='color:white;text-shadow:0px 0px 1px black;'><td>0</td>"
           . "<td>Текщая база</td></tr>";
   while ($stmt->fetch()) echo "<tr onclick='ProdMaster(0,$contra[id])'><td>$contra[id]</td><td>$contra[name]</td></tr>";
   echo "<tr onclick='ProdMaster(0,1000)' style='color:red'><td></td><td>Общий поиск</td></tr>"
            . "<tr onclick='createItem()' style='color:gold;font-size:120%;'><td></td>"
           . "<td style='text-align:center;text-shadow:0px 0px 1px black;cursor:pointer' >Создать товар</td></tr></table>";
} else {
    $_SESSION['contra']=$_REQUEST['contra'];
    if ($_REQUEST['contra']!=0){
$sql="select heap.id,left(heap.partN,15),heap.prodname,heap.price,contra.name from heap
    left join contra on contra.id=heap.contraid left join products on products.hid=heap.id where 1=1 ";
if (isset($_REQUEST['contra'])&&$_REQUEST['contra']==1000);else $sql.=" and (contra.id=$_REQUEST[contra])";
if (isset($_REQUEST['keyword'])){
    $keyword =  $conn->real_escape_string($_GET['keyword']);
    $sql.=" and ((heap.prodName like '%$keyword%') or (partN like '%$keyword%'))";
    $sql.=" order by prodname ";
    $stmt=$conn->prepare($sql);
    }
    
else {
    $sql.=" order by prodname ";
    $stmt=$conn->prepare($sql);}
    }
    else if ($_REQUEST['contra']==0){
        $_SESSION['contra']=$_REQUEST['contra'];
        $sql="select prodIn.hid,products.id,prodIn.prodName,priceIn,TPshName from prodIn
        left join rootorders on rootorders.id=prodIn.roid
        left join tradepoints on rootorders.tpid=tradepoints.id 
        left join products on products.hid=prodIn.hid where 1=1";
        if (isset($_SESSION['tradePoint'])&&$_SESSION['tradePoint']!=0) $sql.=" and rootorders.tpid=$_SESSION[tradePoint]";
        if (isset($_REQUEST['keyword'])) $sql.=" and prodName like '%$_REQUEST[keyword]%'";
            $sql.=' group by hid order by prodName';
            $stmt=$conn->prepare($sql);
    }
if (isset($_REQUEST['catPage'])){$currentPage=$_REQUEST['catPage'];} else {$currentPage=0;}
   $stmt->execute() or die("<h1>ERROR</h1><BR>".mysqli_error($conn));
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   $start=$currentPage*50;
   $stmt=$conn->prepare($sql." LIMIT $start,50");
   $item[]=0;  
   $stmt->bind_result($item['hid'],
                      $item['partN'],
                      $item['prodName'],
                      $item['prodPrice'],
                      $item['contraName']);
   $stmt->execute();
   $stmt->store_result();
   $pages=floor($j/50);
   $i=0;  
   echo '<table id="HeapMenu"><tr>'
   . '<td></td><td></td></tr>';
echo ('<table class="catalog" align=center>'
        . '<tr style="font-size:120%"><th>№</th><th>Кат.№</th><th>Наименование</th><th>Цена</th><th>Поставщик</th></tr>');
         while ($stmt->fetch()){
       echo "<tr><td>$item[hid]</td><td>$item[partN]</td><td style='text-align:left' onclick=\"alert('$item[prodName]')\">$item[prodName]</td>"
           . "<td>$item[prodPrice]</td><td>$item[contraName]</td>"
           ."<td><img src='img/Cart.png' alt='В Заявку' ";
           if ($_REQUEST['contra']!=0) echo " onclick='addToRootCart($item[hid]);' id='add_$item[hid]' /></td></tr>";else
               if ($_REQUEST['contra']==0) echo " onclick='addToRootCart($item[hid]);' id='add_$item[hid]' /></td></tr>";
                  }
          echo '</table>';
          echo '<div id="Navi" align=center>';
echo "<table id='NaviButtons'><tr>
	<td onclick=\"ProdMaster(($currentPage-10)<1?0:$currentPage-10,$_REQUEST[contra],'$keyword')\"><<< -10</td>
          <td onclick=\"ProdMaster(($currentPage-5)<1?0:$currentPage-5,$_REQUEST[contra],'$keyword')\"><< -5</td>
          <td onclick=\"ProdMaster(($currentPage-1)<1?0:$currentPage-1,$_REQUEST[contra],'$keyword')\">< -1</td>
          <td onclick=\"ProdMaster(($currentPage+1)>=$pages?$pages:$currentPage+1,$_REQUEST[contra],'$keyword')\">+1 ></td>
          <td onclick=\"ProdMaster(($currentPage+5)>=$pages?$pages:$currentPage+5,$_REQUEST[contra],'$keyword')\">+5 >></td>
					<td onclick=\"ProdMaster(($currentPage+10)>=$pages?$pages:$currentPage+10,$_REQUEST[contra],'$keyword')\">+10 >>></td>
</tr></table>";
   for ($i=0;$i<=$pages;$i++){
       $page=$i+1;
       if ($currentPage==$i) {echo "$page ";}else
       { if (!(is_null($keyword))) {
                echo "<a href=\"javascript:ProdMaster($i,$_REQUEST[contra],'$keyword')\" style=\"color:yellow;font-size:120%\">$page </a>";}
           else {
               echo "<a href=\"javascript:ProdMaster($i,$_REQUEST[contra])\" style=\"color:yellow;\">$page </a>";}
           }
   }
echo "</div>";
  }
}
  else {echo "<script>Catalog(0)</script>";}