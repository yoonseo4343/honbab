<!DOCTYPE html>
<!-- info.php -->
<html>
<head>
    <title>User List</title>
    <style>
        <?php include 'style.css';?>
        hr {
            border: none;
            height: 1px;
            border-bottom-style: dotted; /* 점선 스타일 설정 */
            border-color: #3D5C4B; /* 구분선의 색상 설정 */
            
        }
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
</div>
<div class="content">
<div class="container-wrapper"> <!-- 수정 -->
    <div class="image-container">
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

    $id = $_GET['id'];
    $image ="http://localhost/img/$id.png";

    echo"<img src='$image' alt='사진' width='400' height='400'>";
    $conn->close();
    ?>
    </div>

    <div class='data-container'>
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

    $id = $_GET['id'];

    //echo "$id"; //아이디 표시

    // 데이터 조회 쿼리 실행
        $sql1 = "SELECT honbabtbl.name, honbabtbl.address, honbabtbl.call, addtbl.level, 
                addtbl.type, honbabtbl.st_t,honbabtbl.end_t,honbabtbl.st_b,honbabtbl.end_b
                FROM honbabtbl 
                INNER JOIN addtbl ON honbabtbl.id = addtbl.id 
                WHERE honbabtbl.id = $id";
        $result1 = $conn->query($sql1);

        $sql2 = " SELECT honbabtbl.id, GROUP_CONCAT(dayofftbl.day) AS days
                FROM honbabtbl
                INNER JOIN dayofftbl ON honbabtbl.id = dayofftbl.id
                WHERE honbabtbl.id = $id
                GROUP BY honbabtbl.id";
        $result2 = $conn ->query($sql2);

        $sql3 = " SELECT honbabtbl.id, GROUP_CONCAT(usetbl.how) AS hows
        FROM honbabtbl
        INNER JOIN usetbl ON honbabtbl.id = usetbl.id
        WHERE honbabtbl.id = $id
        GROUP BY honbabtbl.id";
        $result3 = $conn ->query($sql3);

    // 데이터 출력
    if ($result1 && $result1->num_rows > 0) {
        echo "<ul>";
        while ($row = $result1->fetch_assoc()) {
            $name = $row['name'];
            $address = $row['address'];
            $call = $row['call'];
            $st_t = $row['st_t'];
            $st_b = $row['st_b'];
            $end_t = $row['end_t'];
            $end_b = $row['end_b'];
            $level = $row['level'];
            $type = $row['type'];

            // 기본 정보 데이터 출력
            echo "<h2>$name</h2>";
            echo "<p>주소: $address</p>";
            echo "<p>영업시간: $st_t - $end_t</p>";
            if($st_b!== null){
                echo "<p>브레이크: $st_b - $end_b</p>";
            }
            echo "<p>전화번호: $call</p>";
            echo "<hr>"; // 구분선 추가
            echo "<p>혼밥레벨: level $level</p>";
            echo "<p>종류: $type</p>";
            echo "<hr>"; // 구분선 추가
        }
        echo "</ul>";
    } else {
        echo "조회 불가능.";
    }
    if ($result2 && $result2->num_rows > 0){
        echo "<ul>";
        while ($row = $result2->fetch_assoc()){
            $dayoff = $row['days'];

            echo "<p>휴무일: $dayoff</p>";
        }
        echo "</ul>";
    }
    if ($result3 && $result3->num_rows > 0){
        echo "<ul>";
        while ($row = $result3->fetch_assoc()){
            $use = $row['hows'];

            echo "<p>이용: $use</p>";
        }
        echo "</ul>";
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>
    </div>
</div>
<div class="menu">
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

    $id = $_GET['id'];

    // 데이터 조회 쿼리 실행
    $sql = "SELECT id, m_name, price
            FROM menutbl
            WHERE id = '$id'";
    $result = $conn->query($sql);

    // 데이터 출력
    if ($result && $result->num_rows > 0) {
        echo "<ul>";
        echo"<h3>대표 메뉴</h3>";
        while ($row = $result->fetch_assoc()) {
            $m_name = $row["m_name"];
            $price = $row["price"];
            echo "<li class='menu-item'><span class='m-name'>" . $m_name . "</span><span class='price'>" . $price . "원</span></li>";
        }
        echo "</ul>";
    } else {
        echo "해당 메뉴 정보를 찾을 수 없습니다.";
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>
</div>
</div>
</body>
</html>
