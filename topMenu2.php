<?php 
session_start();
if (isset($_SESSION['userName'])) {
    $userName=$_SESSION['userName'];
    $rb=$rightBlockUser;}
    else {$rb=$rightBlockGuest;}
?>
<div id="top">
        <!-- <div id="LOGO"><img src='img/logo2.png' alt="OilsWorld Logo" /></div> -->
        <div id="workphone">
            <p>ИПБОЮЛ Саламатин М. Е.</p>
            <p>Алтайский край</p>
            <p>с. Волчиха, ул.Толстого, 25</p>
            <p>8-38565-22-2-22</p>
            <p>с. Усть-Чарышская пристань, ул.Ленина, 15</p>
            <p>8-38565-22-2-22</p>
            <p>с. Михайловское, ул.60 лет Октября, 5</p>
            <p>8-38565-22-2-22</p>
            <p>Режим работы: с 9:00 до 18:00</p>
        </div>
    <div id="topCenter">
            <script src="https://player.radiocdn.com/iframe.js?hash=9ef14e2dfdd037cb8f33b1f3d967e2970dee79a7-450-135"></script>
            <p>Быстрый поиск</p>
        <input type="text" size=20 id='Find' oninput="if (this.value.length>2) Catalog(0,this.value);" onchange="Catalog(0,this.value)" />
        </div>    
        <div id="loginForm"><?php echo $rb;?></div>
        <div id="MT">
    <div id="firstMT">
        <table><TR>
                <TD><a href="#" title="Самая главная" onclick="Catalog(0)">Главная</a></td>
                <TD><a href="#" onclick="News()">Новости</a></td></tr></table>
    </div>
    <div id="secondMT">
        <table><tr>
    <TD><a href="#" onclick="About()">О Нас</a></td> 
    <TD><a href="#" onclick="regForm()">Register user</a></td>
         </TR></table>
    </div>
          <?php
            if(isset($_SESSION['userRole']) and $_SESSION['userRole']==0){
                echo $adminPanel;
            }
            ?>     
        <div id="Search">
            <input type="text" size="40" value="Быстрый поиск" id="Find"
                   onfocus="if (this.value=='Быстрый поиск') this.value='';"
                   onclick="if (this.value=='Быстрый поиск') this.value='';"
                   oninput="if (this.value.length>2) Catalog(0,this.value);"
                   onchange="Catalog(0,this.value)" />
        </div>
        </div>
       
    </div>
<?php
?>
