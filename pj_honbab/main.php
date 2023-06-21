<!DOCTYPE html>
<!--main.php-->
<html>
<head>
    <title>My Website</title>
    <style>
        <?php include 'style.css';?>
        input[type=text]{
            width: 300px;
            height: 30px;
            /* font-size: ; */
            border: 0;
            border-radius: 15px;
            outline:none;
            padding-left: 10px;
            background-color: rgb(233, 233, 233);
        }
        input[type=submit]{
            color: #3D5C4B;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gugi&display=swap" rel="stylesheet">
</head>
<body>
    <div class="m_header">
        <div class="title-container">
            <div class="bigfont"><h1 class="title">고독한 혼밥러</h1></div>
            <img class="image" src="http://localhost/img/logo.png" alt="logo">
        </div>
        <form action="search.php" method="GET">
            <input type="text" name="keyword" placeholder="검색">
            <input type="submit" value="검색">
        </form>
        
    </div>
    <div class="main">
        <a href="list.php?location=전북대" class="button button-a">전북대</a> 
        <a href="list.php?location=신정문" class="button button-b">신정문</a>
        <a href="list.php?location=구정문" class="button button-c">구정문</a>
        <a href="list.php?location=사대부고" class="button button-d">사대부고</a>
        <a href="list.php?location=전체" class="button button-e">전체</a>
        <img src="http://localhost/img/map2.png" alt="사진" width="500" height="300">
    <div>
    <!-- 나머지 페이지 내용 작성 -->
</body>
</html>
