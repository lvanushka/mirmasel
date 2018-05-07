<?php session_start();
require_once './inc/config.inc';
$conn=Con();
if (!isset($_SESSION['filter'])) $_SESSION['filter']='All';
$sql='select products.id,left(partn,15),products.prodName,products.vol,TPshName,products.step'
        . ' from heap,products,tradepoints where (heap.id=products.hid) and (products.tpid=tradepoints.id)';
if (isset($_SESSION['userName'])){
 $sql='select products.id,left(partn,15),products.prodName,products.price,ifnull(products.vol-cart.cvol,products.vol),TPshName,products.step from products'
     .' left join tradepoints on products.tpid=tradepoints.id'
     .' left join heap on heap.id=products.hid'
     .' left JOIN (select pid,sum(vol) as cvol from cart group by pid) cart on cart.pid=products.id'
     .' where 1=1 ';
    $_SESSION["location"]="Catalog";
    if(isset($_SESSION['tradePoint'])) {$tradePoint=$_SESSION['tradePoint'];
    if ($tradePoint!=0){$sql.=" and (products.tpid=$tradePoint) ";}}
    }
//if (isset($_REQUEST['catPage'])){
    $currentPage=$_REQUEST['catPage'];
    //} else {$currentPage=0;}
if (isset($_REQUEST['keyword'])&&($_REQUEST['keyword']!="")){
    $keyword =  $conn->real_escape_string($_REQUEST['keyword']);
    switch ($_SESSION['qsOrder']){
        case "by1stlet":$keyword=$keyword.'%';break;
        case "bycont":$keyword='%'.$keyword.'%';break;
        default :$keyword='%'.$keyword.'%';break;
    }
    $sql.=" and ((products.prodName like '$keyword') or (partN like '$keyword') or (note like '$keyword'))";
   // echo "<h3>$keyword</h3>";
    
    }
if (isset($_SESSION['filter'])&&$_SESSION['filter']!='All') $sql.=" and step='$_SESSION[filter]' ";
if (isset($_REQUEST['sort'])) $_SESSION['sort']=$_REQUEST['sort'];
if (isset($_SESSION['sort']))
    switch ($_SESSION['sort']) {
    case "vol":$sql.=" order by products.vol";break;
    case "naim":$sql.=" order by products.prodName";break;
    }
else $sql.=" order by products.prodName";
   $stmt=$conn->prepare($sql);
   // echo "<h3>$sql</h3>";
   $stmt->execute() or die($conn->error);
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   $start=$currentPage*20;
   $stmt=$conn->prepare($sql." LIMIT $start,20");
   $item[]=0;
   
   if (isset($_SESSION['userName'])){
$stmt->bind_result($item['pid'],
$item['partN'],
$item['prodName'],
$item['prodPrice'],
$item['prodVol'],
$item['tradePoint'],
$item['step']);
switch ($_SESSION['filter']){
      case 'i': $int="checked";break;
      case 'f': $float="checked";break;
      case 'A': $filters="checked";break;
      case 'x': $chem="checked";break;
      default:$all="checked";break;
  }
echo "<div id='filter'>\n"
     ."<input id='all' onchange='radioCh()' name='mes' type='radio' value='All' $all/><label for='all'>Всё</label>\n"
     ."<input id='int' onchange='radioCh()' name='mes' type='radio' value='i' $int/><label for='int'>Фасовка</label>\n"
     ."<input id='float' onchange='radioCh()' name='mes' type='radio' value='f' $float/><label for='float'>Розлив</label>\n"
     ."<input id='filters' onchange='radioCh()' name='mes' type='radio' value='A' $filters/><label for='filters'>Фильтра</label>\n"
     ."<input id='chem' onchange='radioCh()' name='mes' type='radio' value='x' $chem/><label for='chem'>Химия/Тосол</label>\n"
        . "</div>\n";
echo ('<table class="catalog" id="Cat" align=center>'
 . '<tr style="font-size:120%"><th>№</th><th onclick=\'Catalog(0,"","naim")\'>Наименование</th><th>Цена</th>'
        . '');}
   else {
$stmt->bind_result($item['pid'],
$item['partN'],
$item['prodName'],
$item['prodVol'],
$item['tradePoint'],
$item['step']);
echo '<table class="catalog" id="Cat" align=center>'
        . '<tr style="font-size:120%"><th style="">Кат.№</th><th onclick=\'Catalog(,,"naim")\'>Наименование</th>'
        . '';
   }
   $stmt->execute();
   $stmt->store_result();
   $pages=floor($j/20);
   $i=0;  
if ($tradePoint==0) echo '<th>Место</th>';
echo '<th onclick=\'Catalog(0,"","vol")\'>Наличие</th><th><img src="img/printer.png" alt="Печать" width="32" height="32"  onclick="printList('.$tradePoint.')"/></th></tr>'
        . '';
         while ($stmt->fetch()){
	$prodShortName=substr($item[prodName],0,100);
	if ((isset($_SESSION['userRole'] ))&&($_SESSION['userRole']<=1)){
if ($item[prodVol]==0) echo '<tr style="color:white;background:rgba(255,0,0,0.6);text-shadow:1px 0px 1px black">';else
if (($item[prodVol]>0)&&($item[prodVol]<=3) ) echo '<tr style="color:black;background:rgba(255,255,0,0.6)">';else
if (($item[prodVol]>3)&&($item[prodVol]<=7) ) echo '<tr style="color:black;background:rgba(0,255,0,0.6)">';else
echo "<tr>";
} else echo "<tr>";
echo "<td>$item[pid]</td><td onclick=\"alert('$item[prodName]')\">$prodShortName</td>";
                  if (isset($_SESSION['userName'])){echo "<td id='price_$item[pid]'>$item[prodPrice]</td>";}
if ($tradePoint==0) echo '<td>'.$item['tradePoint'].'</td>';
               echo "<td id='vol_$item[pid]'>";echo ($item['step']!='f'?round($item[prodVol])."</td>":$item[prodVol]."</td>");
               //echo "<td>$item[prodPrice]</td>";
       if (isset($_SESSION['userName']))
            echo "<td style='background:none'><img src='img/Cart.png' alt='В корзину' onclick='addToCart($item[pid])'/></td></tr>\n";
       //else echo "<td style='width:5%'>$item[tradePoint]</td></tr>";
                  }
          echo '</table>';
          echo '<div id="Navi" align=center>';
          echo "<table id='NaviButtons'>\n<tr>
	  <td onclick=\"Catalog(($currentPage-10)<1?0:$currentPage-10,'$keyword')\"><<< -10</td>\n
          <td onclick=\"Catalog(($currentPage-5)<1?0:$currentPage-5,'$keyword')\"><< -5</td>\n
          <td onclick=\"Catalog(($currentPage-1)<1?0:$currentPage-1,'$keyword')\">< -1</td>\n
          <td onclick=\"Catalog(($currentPage+1)>=$pages?$pages:$currentPage+1,'$keyword')\">+1 ></td>\n
          <td onclick=\"Catalog(($currentPage+5)>=$pages?$pages:$currentPage+5,'$keyword')\">+5 >></td>\n
          <td onclick=\"Catalog(($currentPage+10)>=$pages?$pages:$currentPage+10,'$keyword')\">+10 >>></td>\n
</tr></table>\n";
//<tr><td>$currentPage</td><td>$pages</td><td></td><td></td></tr> //debug
   for ($i=0;$i<=$pages;$i++){
       $page=$i+1;
       if ($currentPage==$i) {echo "$page ";}else
       { if (!(is_null($keyword))) { 
                echo "<a href=\"javascript:Catalog($i,'$keyword')\" style='color:yellow;font-size:120%'>$page </a>\n";}
           else {
               echo "<a href='javascript:Catalog($i)' style='color:yellow;'>$page </a>\n";}
           }
   }
  echo "</div>\n";