<?php
if (!isset($_SESSION['userName'])){
    $grettings=<<<RBG
<table>
<tr><td><input type="text" name="uname" id="userName" value="Имя" width="20"
onclick="if (this.value=='Имя') this.value=''" /></td></tr>
<tr><td><input type="password" name="upass" id="userPass" value="Пароль"
onclick="if (this.value=='Пароль') this.value=''" width="20" /></td></tr>
<tr><td><input type="button" value=" Регистрация "onclick="regForm()"/>
<input type="button" value="Вход" id="loginButton" onclick="auth('login')"/></td>
</tr>
</table>
RBG;
} else if ((isset($_SESSION['userRole']))&&($_SESSION['userRole']==1)){
$user=$_SESSION['userName'];
$grettings=<<<RBS
<table>
<tr><td colspan='2'>Добро пожаловать <h3 style="font-size:120%;color:red;padding:0;margin:0;">$user</h3> </td></tr>
<tr><td colspan='2'>в Мир Масел</td></tr>
<tr><td colspan='2'>Мы рады приветствовать Вас</td></tr>
<tr><td onclick="Order('showCart');"
    style="background:rgba(255,255,100,0.8);cursor:pointer;border-radius:20px;color:red;">Корзина</td>
    <td onclick="Order('ordersList');" 
    style="background:rgba(255,255,100,0.8);cursor:pointer;border-radius:20px; color:blue ;">Заказы</td></tr>
<tr><td colspan='2' onclick='alert("Your $")'
    style='background:rgba(255,255,100,0.8);cursor:pointer;border-radius:20px;'>Cчёт</td></tr>
<tr><td  colspan='2' align="right"><input type="button" value="Выход" id="exitButton" onclick="auth('logout')" /></td></tr>
</table>   
RBS;
} else 
{
$user=$_SESSION['userName'];
    $grettings=<<<RBU
<table>
<tr><td colspan='2'>Добро пожаловать <h3 style="font-size:120%;color:red;padding:0;margin:0;">$user</h3> </td></tr>
<tr><td colspan='2'>в Мир Масел</td></tr>
<tr><td colspan='2'>Мы рады приветствовать Вас</td></tr>
<tr><td onclick="Order('showCart');"
    style="background:rgba(255,255,100,0.8);cursor:pointer;border-radius:20px;color:red;">Корзина</td>
    <td onclick="Order('ordersList');" 
    style="background:rgba(255,255,100,0.8);cursor:pointer;border-radius:20px; color:blue ;">Заказы</td></tr>
<tr><td colspan='2' onclick='alert("Your $")'
    style='background:rgba(255,255,100,0.8);cursor:pointer;border-radius:20px;'>Cчёт</td></tr>
<tr><td  colspan='2' align="right"><input type="button" value="Выход" id="exitButton" onclick="auth('logout')" /></td></tr>
</table>   
RBU;
}
if (isset($_SESSION["userRole"])&&($_SESSION["userRole"]==0))
{
    $adminPanel=<<<AP
            <script src="js/adminPanel.js"></script> 
            <script src="js/sellerPanel.js"></script>
<div class="firstMT"><table><tr>
        <td onclick="CashFlow()">Ден. поток</td>
        <td onclick="ProdFlow()">Тов. поток</td>     
        </tr></table>
        </div>
<div class="secondMT"><table><tr>
        <td onclick="Users()">Пользователи</td>
        <td onclick="Journal()">Журнал</td>
   </tr></table></div>

<div class="firstMT"><table><tr>
        <td onclick="ProdMaster()">Подать заявку</td>
        <td onclick="PersMaster()">Кадры</td>     
        </tr></table>
        </div>
<div class="secondMT"><table><tr>
        <td onclick="MoveMaster">Перем.Товара</td>
        <td onclick="Report()">Отчёт</td>
   </tr></table></div>
AP;
}
else 
    {$adminPanel="";}
if (isset($_SESSION["userRole"])&&($_SESSION["userRole"]<=1))
{$sellerPanel=<<<SP
        <script src="js/sellerPanel.js"></script>
<div class="firstMT"><table><tr>
        <td onclick="Order('ordersList')">Заказы</td>
        <td onclick="rootOrder('ordersList')">Заявки</td>     
        </tr></table>
        </div>
        <div class="secondMT"><table><tr>
        <td onclick="Shippers()">Курьеры</td>
        <td onclick="Orderers()">Заказчики</td>
   </tr></table></div>
SP;
}
else 
{$sellerPanel="";}
?>