<?php
session_start();
require_once './inc/config.inc';
//Функция проверки баланса торговой точки
function balance($tpid,$conn){
    $balanceQ='select `In`-`Out` as Rest from (select ifnull(sum(prodflow.price*prodflow.vol),0) as `In`
from prodflow left join orders on orders.id=prodflow.oid where orders.tpid=?) CashIn
join (select tpid,ifnull(sum(cashout),0) as `Out` from cashout where tpid=?) CashOut';
if (!$stmt=$conn->prepare($balanceQ)) echo '<h2 class="subWindowHeader">Error while preparing Statement:'.$conn->error.'</h2>';
if (!($stmt->bind_param('ii',$tpid,$tpid)))
        echo '<h2 class="subWindowHeader">Error while binding Params:'.$stmt->error.'</h2>';
if (!($stmt->bind_result($Balance)))
        echo '<h2 class="subWindowHeader">Error while binding result:'.$stmt->error.'</h2>';
if(!($stmt->execute())) echo '<h2 class="subWindowHeader">Error while executing PS '.$stmt->error.'</h2>';
if (!($stmt->fetch()))  echo '<h2 class="subWindowHeader">Error while fetchin result:'.$stmt->error.'</h2>';
return round($Balance);
}
$tpid=$_SESSION['tradePoint'];
$_SESSION['location']='cashflow';
$conn=Con(); 
if (isset($_SESSION['userRole'])){
    if ($_SESSION['userRole']<=1 &&(isset($_REQUEST['getBalance']))) {echo balance($_REQUEST['tpid'],$conn);die();}
    if ($_SESSION['userRole']==0){
        if ((isset($_REQUEST['showCash']))&&$_REQUEST['showCash']==TRUE){
             if(isset($_SESSION['tradePoint'])&&($_SESSION['tradePoint']!=0)){
            //Если Выбрано место продаж рисуем журнал 
        echo '<h1 class="windowHeader">Журнал</h1><h2 class="subWindowHeaderP"> TP# '.$_SESSION['tradePoint'].'</h2>';
$sql='select cashout.id,person.`name`,cashout.`date`+interval 4 hour,cashout.cashIn,cashout.cashout,cashout.Balance,cashout.`desc` from cashout
left join person on person.id=cashout.uid where cashout.tpid=? ORDER BY cashout.`date` DESC';

if (!$stmt=$conn->prepare($sql)) echo '<h2 class="subWindowHeaderN">Error while preparing Statement:'.$conn->error.'</h2>';
if (!($stmt->bind_param('i',$tpid)))
        echo '<h2 class="subWindowHeaderN">Error while binding Params:'.$stmt->error.'</h2>';
if (!($stmt->bind_result($cash['id'],$cash['user'],$cash['date'],
        $cash['cashIn'],$cash['cashOut'],$cash['Balance'],$cash['descr'])))
        echo '<h2 class="subWindowHeaderN">Error while binding result:'.$stmt->error.'</h2>';
if(!($stmt->execute())) echo '<h2 class="subWindowHeaderN">Error while executing PS '.$stmt->error.'</h2>';
$stmt->store_result();
echo '<table class="catalog"><tr style="background:black;color:white;text-shadow:-1px -1px 2px purple"><th>#</th><th>Пользователь</th><th>Дата / Время</th>'
        .'<th style="color:lightgreen">в Кассе</th><th style="color:red">Снято</th>'
        . '<th style="color:gold">Остаток</th><th>Описание</th></tr>';
while ($stmt->fetch()){
    echo '<tr><td>'.$cash['id'].'</td><td>'.$cash['user'].'</td><td>'.$cash['date']
        .'</td><td>'.$cash['cashIn'].'</td><td>'.$cash['cashOut']
        .'</td><td>'.$cash['Balance'].'</td><td>'.$cash['descr'].'</td></tr>';
} echo '</table>';
        }
        //если место продаж не выбрано - выбираем
        else {echo '<h1 class="windowHeader">Выбор места продаж</h1>';
$sql='select tradepoints.id,TPname,
ifnull(balance.cashIn,0) as cashIn,ifnull(cashouts,0) as cashOut,
(ifnull(balance.cashIn,0)-ifnull(cashouts,0)) as rest 
from tradepoints left join (select orders.tpid,sum(prodflow.price*prodflow.vol) as cashIn
from prodflow left join orders on orders.id=prodflow.oid where orders.paid is true group by orders.tpid) balance on balance.tpid=tradepoints.id
left join (select tpid,sum(cashout) as cashouts from cashout) cashout  on cashout.tpid=tradepoints.id';
if (!$stmt=$conn->prepare($sql)) echo '<h2 class="subWindowHeaderN">Error while preparing Statement:'.$conn->error.'</h2>';
if (!($stmt->bind_result($tpList['id'],$tpList['tpname'],$tpList['cashIn'],$tpList['cashOut'],$tpList['balance'])))
        echo '<h2 class="subWindowHeaderN">Error while binding result:'.$stmt->error.'</h2>';
if(!($stmt->execute())) echo '<h2 class="subWindowHeaderN">Error while executing PS '.$stmt->error.'</h2>';
$stmt->store_result();
echo '<table class="catalog"><tr style="background:black;color:white;text-shadow:-1px -1px 2px purple"><th>#</th>'
. '<th>Место продаж</th><th style="color:lightgreen">Приход</th><th style="color:red">Расход</th>'
        . '<th style="color:gold">Баланс</th>'
        .'</tr>';
        //. '<th>Изъятие</th></tr>';
while ($stmt->fetch()){
    echo '<tr><td>'.$tpList['id'].'</td><td style="cursor:pointer" onclick="TPSelector('.$tpList['id'].')">'.$tpList['tpname']
        .'</td><td>'.round($tpList['cashIn']).'</td><td>'.round($tpList['cashOut'])
        .'</td><td>'.round($tpList['balance']).'</td><td class="cfBtn" onclick="takeCash('.$tpList['id'].');disableControl(this);">Изъять</td></tr>';
}
echo '</table>';
        }
    }
    else if (isset($_REQUEST['takeCash'])&&isset($_REQUEST['tpid'])){
$balance= balance($_REQUEST['tpid'], $conn);
if ($_REQUEST['takeCash']>$balance) die ('<h2 class="subWindowHeaderN">Сумма, запрошенная для изъятия превышает баланс '.$balance.'</h2>');
else {
$getCashQ='insert into cashout(tpid,uid,cashIn,cashOut,Balance,`desc`)
values (?,?,?,?,?,"Изъятие наличности")';
$rest=$balance-$_REQUEST['takeCash'];
if (!$stmt=$conn->prepare($getCashQ)) echo '<h2 class="subWindowHeader">Error while preparing Statement:'.$conn->error.'</h2>';
if (!($stmt->bind_param('iiiii',$_REQUEST['tpid'],$_SESSION['userID'],$balance,$_REQUEST['takeCash'],$rest)))
        echo '<h2 class="subWindowHeader">Error while binding Params:'.$stmt->error.'</h2>';
if(!($stmt->execute())) echo '<h2 class="subWindowHeader">Error while executing PS '.$stmt->error.'</h2>';
die('done');
//echo '<script>TPSelector('.$tpid.');</script>';
    }
}
    }
}

else {die("Access Denied ");}