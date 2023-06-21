<!DOCTYPE html>
<!-- list.php -->
<html>
<head>
    <title>User List</title>
    <style>
        <?php include 'style.css';?>
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            adjustSidebarHeight();
        });

        window.addEventListener('resize', function() {
            adjustSidebarHeight();
        });

        function adjustSidebarHeight() {
            var sidebar = document.querySelector('.sidebar');
            var content = document.querySelector('.sidebar-content');

            var contentHeight = content.scrollHeight;
            sidebar.style.height = contentHeight + 'px';
        }
        function checkAll() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');

            if (document.getElementById("all").checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = true;
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = false;
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gugi&display=swap" rel="stylesheet">
</head>
<body> 
    <!-- 페이지 다 동일하게 하기 -->
    <div class="header">
        <h1 class="title">고독한 혼밥러</h1>
        <nav class="nav">
            <a href="main.php"><img src="http://localhost/img/home2.png" alt="logo" width='50' height='50'></a>
            
        </nav>
    </div>


    <div class="sidebar">
        <div class="sidebar-content">
    <form method="POST" action="">
        <br>
        <input type="checkbox" id="all" name="all" onclick="checkAll()"> 전체<br>
    
        <h3>혼밥 레벨</h3>
        <input type="checkbox" name="level[]" value="0">
        <label for="level0">0단계</label>

        <input type="checkbox" name="level[]" value="1">
        <label for="level1">1단계</label><br>
        
        <input type="checkbox" name="level[]" value="2">
        <label for="level2">2단계</label>
        
        <input type="checkbox" name="level[]" value="3">
        <label for="level3">3단계</label><br>

        <h3>종류</h3>
        <input type="checkbox" name="type[]" value="한식">
        <label for="한식">한식</label><br>
        
        <input type="checkbox" name="type[]" value="양식">
        <label for="양식">양식</label><br>
        
        <input type="checkbox" name="type[]" value="일식">
        <label for="일식">일식</label><br>
        
        <input type="checkbox" name="type[]" value="중식">
        <label for="중식">중식</label><br>
        
        <input type="checkbox" name="type[]" value="아시안">
        <label for="아시안">아시안</label><br>
        
        <input type="checkbox" name="type[]" value="기타">
        <label for="기타">기타</label><br>

        <input type="checkbox" name="type[]" value="종합">
        <label for="종합">종합</label><br>
        
        <h3>매장/포장 여부</h3>
        <input type="checkbox" name="how[]" value="매장">
        <label for="매장">매장</label>
        
        <input type="checkbox" name="how[]" value="포장">
        <label for="포장">포장</label><br>
        
        <h3>브레이크타임</h3>
        <input type="checkbox" name="st_b" value="NULL">
        <label for="">없음</label><br>
        <br>
        <button type="submit" name="submit">선택</button>
    </form>
    </div>
    </div>
<div class="content">
    <div class="content-container">
    <ul>
        <li class='list-title'><span class='list-name'>가게 이름
                    <br></span><span class='list-menu'>대표 메뉴
                    </span><br><span class='price'> 평균 가격</span></li>
    </ul>
    
    <?php
    // MySQL 데이터베이스 연결 정보 설정
    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "honbabdb";

    // 데이터베이스에 연결
    $conn = new mysqli($host, $username, $password, $database);

    // 연결 오류 확인
    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    $arr_err = array();

    //레벨값 가져오기
    $arr_lev = array();
    if(!empty($_POST['level'])) {
        for($i=0;$i<count($_POST['level']);$i++){
            $level_temp = $_POST['level'][$i];
            array_push($arr_lev, $level_temp);
        }
    }
    else array_push($arr_err, "혼밥 레벨");

    //타입값 가져오기
    $arr_ty = array();
    if(!empty($_POST['type'])) {
        for($i=0;$i<count($_POST['type']);$i++){
            $type_temp = $_POST['type'][$i];
            array_push($arr_ty, $type_temp);
        }
    }
    else array_push($arr_err, "종류");

    //매장포장값 가져오기
    $arr_h = array();
    if(!empty($_POST['how'])) {
        for($i=0;$i<count($_POST['how']);$i++){
            $how_temp = $_POST['how'][$i];
            array_push($arr_h, $how_temp);
        }
    }
    else array_push($arr_err, "매장/포장 여부");

    $location = $_GET['location'];

    

    if (!isset($_POST['submit'])) { //필터를 적용하지 않았을 때
        if($location == "전체") {

             //전체 초기창
    $sql_s_e = "SELECT honbabtbl.id, honbabtbl.name
    FROM honbabtbl
    INNER JOIN addtbl ON honbabtbl.id = addtbl.id";
    $result_s_e = $conn->query($sql_s_e);

            echo "<ul>";
            while ($row = $result_s_e->fetch_assoc()) {
                $name = $row["name"];
                $id = $row["id"];
                //echo "<li>" . $row["name"] . "</li>";
                //echo "<a href='info.php?id=$id'>$name</a><br>"; //출력

                //대표메뉴
                $sql_m = " SELECT honbabtbl.id, GROUP_CONCAT(menutbl.m_name) AS menus
                FROM honbabtbl
                INNER JOIN menutbl ON honbabtbl.id = menutbl.id
                WHERE honbabtbl.id = $id
                GROUP BY honbabtbl.id";
                $result_m = $conn ->query($sql_m);

                //평균값
                $sql_p = "SELECT AVG(price) AS average_price 
                FROM menutbl
                WHERE id = $id;
                ";
                $result_p = $conn->query($sql_p);
                
                if ($result_p->num_rows > 0) {
                    $row = $result_p->fetch_assoc();
                    $averagePrice = $row["average_price"];
                    $formattedPrice = number_format($averagePrice, 0);
                    if ($result_m->num_rows > 0) {
                        $row_m = $result_m->fetch_assoc();
                        $sig_menu = $row_m["menus"];
                    
                        echo "<li class='list-item'><span class='list-name'><a href='info.php?id=$id'class='link'>$name</a>
                        <br></span><span class='list-menu'><a href='info.php?id=$id'class='link'>$sig_menu</a>
                        </span><br><span class='price'> <a href='info.php?id=$id'class='link'>$formattedPrice 원</a></span></li>";
                    }
                }

            }
        }
        else {

            
    //위치별 초기창
    $sql_start = "SELECT honbabtbl.id, honbabtbl.name
    FROM honbabtbl
    INNER JOIN addtbl ON honbabtbl.id = addtbl.id
    WHERE location = '$location'";
    $result_s = $conn->query($sql_start);
    
   
            echo "<ul>";
            while ($row = $result_s->fetch_assoc()) {
                $name = $row["name"];
                $id = $row["id"];
                //echo "<li>" . $row["name"] . "</li>";
                //echo "<a href='info.php?id=$id'>$name</a><br>"; //출력
                
                //대표메뉴
                $sql_m = " SELECT honbabtbl.id, GROUP_CONCAT(menutbl.m_name) AS menus
                FROM honbabtbl
                INNER JOIN menutbl ON honbabtbl.id = menutbl.id
                WHERE honbabtbl.id = $id
                GROUP BY honbabtbl.id";
                $result_m = $conn ->query($sql_m);

                //평균값
                $sql_p = "SELECT AVG(price) AS average_price 
                FROM menutbl
                WHERE id = $id;
                ";
                $result_p = $conn->query($sql_p);
                
                if ($result_p->num_rows > 0) {
                    $row = $result_p->fetch_assoc();
                    $averagePrice = $row["average_price"];
                    $formattedPrice = number_format($averagePrice, 0);
                    if ($result_m->num_rows > 0) {
                        $row_m = $result_m->fetch_assoc();
                        $sig_menu = $row_m["menus"];
                    
                        echo "<li class='list-item'><span class='list-name'><a href='info.php?id=$id'class='link'>$name</a>
                        <br></span><span class='list-menu'><a href='info.php?id=$id'class='link'>$sig_menu</a>
                        </span><br><span class='price'> <a href='info.php?id=$id'class='link'>$formattedPrice 원</a></span></li>";
                    }
                }
            }
        }
    } else {

        // 데이터 조회 쿼리 실행
    if($location == '전체'){
        $sql = "SELECT distinct honbabtbl.id, honbabtbl.name
        FROM honbabtbl
        INNER JOIN addtbl ON honbabtbl.id = addtbl.id
        INNER JOIN usetbl ON honbabtbl.id = usetbl.id
        WHERE addtbl.level IN(".implode(',',$arr_lev).")
        and addtbl.type IN('".implode("','", $arr_ty)."')
        and usetbl.how IN('".implode("','", $arr_h)."')";
        if (isset($_POST['st_b'])) {
            $sql .= " AND honbabtbl.st_b IS NULL";
        }
        $result = $conn->query($sql);
    }
    else{
        $sql = "SELECT distinct honbabtbl.id, honbabtbl.name
        FROM honbabtbl
        INNER JOIN addtbl ON honbabtbl.id = addtbl.id
        INNER JOIN usetbl ON honbabtbl.id = usetbl.id
        WHERE location = '$location'
        and addtbl.level IN(".implode(',',$arr_lev).")
        and addtbl.type IN('".implode("','", $arr_ty)."')
        and usetbl.how IN('".implode("','", $arr_h)."')";
        if (isset($_POST['st_b'])) {
            $sql .= " AND honbabtbl.st_b IS NULL";
        }
        $result = $conn->query($sql);
    }

        if(!empty($arr_err)) {
            echo implode(", ", $arr_err), "를(을) 선택해주세요.";
        } else if($result && $result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                $name = $row["name"];
                $id = $row["id"];
                //echo "<li>" . $row["name"] . "</li>";
                //echo "<a href='info.php?id=$id'>$name</a><br>"; //출력

                //대표메뉴
                $sql_m = " SELECT honbabtbl.id, GROUP_CONCAT(menutbl.m_name) AS menus
                FROM honbabtbl
                INNER JOIN menutbl ON honbabtbl.id = menutbl.id
                WHERE honbabtbl.id = $id
                GROUP BY honbabtbl.id";
                $result_m = $conn ->query($sql_m);

                //평균값
                $sql_p = "SELECT AVG(price) AS average_price 
                FROM menutbl
                WHERE id = $id;
                ";
                $result_p = $conn->query($sql_p);

                if ($result_p->num_rows > 0) {
                    $row = $result_p->fetch_assoc();
                    $averagePrice = $row["average_price"];
                    $formattedPrice = number_format($averagePrice, 0);
                    if ($result_m->num_rows > 0) {
                        $row_m = $result_m->fetch_assoc();
                        $sig_menu = $row_m["menus"];
                    
                    echo "<li class='list-item'><span class='list-name'><a href='info.php?id=$id'class='link'>$name</a>
                    <br></span><span class='list-menu'><a href='info.php?id=$id'class='link'>$sig_menu</a>
                    </span><br><span class='price'> <a href='info.php?id=$id'class='link'>$formattedPrice 원</a></span></li>";
                    }
                }
            }
            echo "</ul>";
        } else if($result && $result->num_rows == 0) {
            echo "해당 조건의 가게가 없습니다.";
        }    
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>
</div>
</div>
</body>
</html>
