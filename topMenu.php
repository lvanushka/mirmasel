<?php 
session_start();
if (isset($_SESSION['userName'])) $userName=$_SESSION['userName'];?>
<div id="top">
        <!-- <div id="LOGO"><img src='img/logo2.png' alt="OilsWorld Logo" /></div> -->
        <div id="workphone">
<div class="tpDesc">
            <p>ИП Саламатин М. Е.</p>
            <p>Алтайский край</p>
            <p>с. Волчиха, ул.Толстого, 25</p>
            <p>8-906-941-86-43</p>
</div>
<div class="tpDesc">
            <p>с. Усть-Чарышская пристань, ул.Ленина, 15</p>
            <p>8-903-957-40-48</p>
</div>
<div class="tpDesc">
            <p>с. Михайловское, ул.Островского, 48</p>
            <p>8-</p>
</div>
<div class="tpDesc">
            <p>с. Степное Озеро, ул.Нефтебазная, 5</p>
            <p>8-909-502-17-49</p>
            <p>Режим работы: с 9:00 до 18:00</p>
</div>
        </div>
        <div id="loginForm" style="text-shadow:1px 1px black;"><?php echo $grettings;?></div>
        <div id="MT">
    <div class="firstMT">
        <table><TR>
                <TD onclick="Catalog(0)">Главная</td>
                <TD onclick="News()">Новости</td></tr></table>
    </div>
    <div class="secondMT">
        <table><tr>
    <TD onclick="About()">О Нас</td> 
    <TD onclick="ChillTrax()">ChillTrax</td>
         </TR></table>
    </div>
           <div id="adminPanel"><?php echo $adminPanel;?></div>
           <div id="sellerPanel"><?php echo $sellerPanel;?></div>
           <input type="search" size="40" value="Быстрый поиск" id="Find"
                   onmouseover='$("#Find").css("color","green")'
                   onmouseout='$("#Find").css("color","black")'
                   onclick="if (this.value=='Быстрый поиск') this.value='';" /><br>
           <?php
           switch ($_SESSION['qsOrder']){
           case 'by1stlet':$by1stlet='checked';break;
           case 'bycont':$bycont='checked';break;
           default:$bycont='checked';break;} 
     echo '<input id="by1stlet" type="radio" onchange="qsChangeOrder()" name="qsOrder" value="by1stlet" '.$by1stlet.' /><label for="by1stlet" >Сначала</label>
           <input id="bycont" type="radio" onchange="qsChangeOrder()" name="qsOrder" value="bycont" '.$bycont.' /><label for="bycont" >Везде</label>'; 
           ?> 
        </div>
    </div>