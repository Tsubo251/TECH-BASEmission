<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<?php

$dsn = 'mysql:dbname=***;host=localhost';
    $user = '***';
    $password = '***';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS tb_thread"//「もしまだこのテーブルが存在しないなら」
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//自動で登録されているナンバリングのこと
    . "name char(32),"//名前を入れる(文字列、半角英数で32文字)
    . "comment TEXT,"//コメントを入れる(文字列、長めの文章も入る)
    . "date DATETIME,"
    ."password TEXT"
    .");";
    $stmt = $pdo->query($sql);

//削除機能
if(!empty($_POST["del"]) && !empty($_POST["delpass"])){
    $del = $_POST["del"];
    $delpass = $_POST["delpass"];
    $sql = 'SELECT * FROM tb_thread'; 
    $stmt = $pdo->query($sql); 
    $results = $stmt->fetchAll(); 
        
        foreach ($results as $row){ 
            
           if ($row["id"] == $del && $delpass == $row["password"]){ 
                $id = $row["id"]; 
                 $sql = 'delete from tb_thread where id=:id'; 
                $stmt = $pdo->prepare($sql); 
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute(); 
             
            }
        }  
    }
//編集対象番号が送信されたとき
if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["hidnum"]) && !empty($_POST["pass"])){
    $ednum = $_POST["hidnum"];
    $pass = $_POST["pass"];
    
    $sql = 'SELECT *FROM tb_thread';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach($results as $row){
        if($row["id"] == $ednum && $row["password"] == $pass){
            $id = $row["id"];
            $name = $_POST["name"];
            $comment = $_POST["str"];
            $date = date("Y/m/d H:i:s");
            
            $sql = 'UPDATE tb_thread SET name=:name,comment=:comment ,date=:date WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

if(!empty($_POST["ednum"]) && !empty($_POST["edpass"])){
    $ednum = $_POST["ednum"];
    $edpass = $_POST["edpass"];
    
    $sql = 'SELECT * FROM tb_thread';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach($results as $row){
        if($row["id"] == $ednum && $row["password"] == $edpass){
            $edname = $row["name"];
            $edstr = $row["comment"];
            $edpass = $row["password"];
        }
    }
}

if(!empty($_POST["str"]) && !empty($_POST["name"]) &&!empty($_POST["pass"]) && empty($_POST["hidnum"])){
    $sql = $pdo -> prepare("INSERT INTO tb_thread (name, comment, date, password) VALUES (:name, :comment, :date, :password)"); 
    $sql -> bindParam(':name', $name, PDO::PARAM_STR); 
    $sql -> bindParam(':comment', $str, PDO::PARAM_STR); 
    $sql -> bindParam(':date', $date, PDO::PARAM_STR); 
    $sql -> bindParam(':password', $pass, PDO::PARAM_STR); 
    $name = $_POST["name"]; 
    $str = $_POST["str"]; 
    $date = date("Y/m/d H:i:s");
    $pass = $_POST["pass"]; 
    $sql -> execute(); 
}

?>

<body>
    <form action=""method="post">
        <p>ここに入力：<br>
        <input type= "text" name="name" placeholder="名前" value = "<?php if(isset($edname)){echo $edname;}?>"></p>
        <input type= "text" name="str"  placeholder="コメント" value = "<?php if(isset($edstr)){echo $edstr;}?>"></p>
        <input type= "text" name="pass" placeholder="パスワード"></p>
        <input type= "hidden" name="hidnum" value = "<?php if(isset($ednum)){echo $ednum;}?>"></p>
        <input type="submit" value="コメントする">
        
    <form action = ""method ="post">
        <p><input type = "text" name = "del" placeholder = "削除対象番号"></p>
        <input type = "text" name = "delpass" placeholder = "パスワード"></p>
        <input type = "submit" value = "削除">
        
    <form action = ""method = "post">
        <p><input type = "text" name = "ednum" placeholder = "編集対象番号"></p>
        <input type = "text" name = "edpass" placeholder = "パスワード"></p>
        <input type = "submit" value = "編集">
</form>

<?php
$sql = 'SELECT * FROM tb_thread';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].' ';
        echo "<hr>";
        }

?>

</body>
</html>