<!DOCTYPE html>
<!-- search.php -->
<html>
<head>
    <title>User List</title>
    <style>
        <?php include 'style.css';?>
    </style>
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

    // GET 요청으로 전달된 검색어 가져오기
    $keyword = $_GET["keyword"];

    // 데이터베이스에서 검색 쿼리 실행
    $sql = "SELECT * FROM honbabtbl
            WHERE honbabtbl.name LIKE '%$keyword%'";
    $result = $conn->query($sql);

    // 검색 결과 출력
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
            $id = $row["id"];
            //echo "<li>" . $row["name"] . "</li>";
            // echo "<a href='info.php?id=$id'>$name</a><br>";
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
    } else {
        echo "검색 결과가 없습니다.";
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>
    </div>
</div>

</body>
</html>
