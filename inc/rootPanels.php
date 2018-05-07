<?php
$ROPanel=<<<ROP
<table id="HeapMenu" class="rootPanels"><tr>
    <td onclick="ProdMaster()">Добавить</td>
        <td onclick="rootOrder('showCart')">Текущая заявка</td>
        <td onclick="rootOrder('ordersList')">Список заявок</td>
        <td onclick="rootOrder('showCart')">ЕщЁ</td></tr></table>
ROP;
$reportPanel=<<<RP
<table id="reportsMenu" class="rootPanels"><tr>
    <td onclick="Report()">Общий</td>
        <td onclick="ProdMaster()">Текущая заявка</td>
        <td onclick="ProdMaster()">Список заявок</td>
        <td onclick="ProdMaster()">ЕщЁ</td></tr></table>
RP;
