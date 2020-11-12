<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$sql = "CREATE TABLE IF NOT EXISTS board2"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "time TEXT,"
	. "kagi TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
    if(!empty($_POST['edit'])){
       $id = $_POST['num_edit'];
       $sql = 'SELECT * FROM board2 WHERE id=:id';
       $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
       $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
       $stmt->execute();                             // ←SQLを実行する。
       $results = $stmt->fetchAll(); 
	   foreach ($results as $row){
          if($id == $row['id']){
             $edit_name = $row['name'];
             $edit_comment = $row['comment'];
             $edit_number = $id;
          }
       }
    }
    else if(!empty($_POST['delete'])){
       $id = $_POST['num_delete'];
       $kagi = $_POST["kagi"];
       $sql = 'delete from board2 where id=:id AND kagi=:kagi';
	   $stmt = $pdo->prepare($sql);
	   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	   $stmt->bindParam(':kagi', $kagi, PDO::PARAM_STR);
	   $stmt->execute();
    }
    else{
       if((!empty($_POST['name'])) && (!empty($_POST['comment'])) && (!empty($_POST['kagi']))){
          $name = $_POST["name"];
          $comment = $_POST["comment"];
          $time = date("Y/m/d H:i:s");
          $kagi = $_POST["kagi"];
          if(!empty($_POST['num_flag'])){
             $id = $_POST["num_flag"];
             $sql = 'UPDATE board2 SET name=:name,comment=:comment,time=:time WHERE id=:id AND kagi=:kagi';
    	     $stmt = $pdo->prepare($sql);
	         $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	         $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	         $stmt->bindParam(':kagi', $kagi, PDO::PARAM_STR);
         	 $stmt->bindParam(':time', $time, PDO::PARAM_STR);
	         $stmt->execute();
          }
          else{
              	$sql = $pdo -> prepare("INSERT INTO board2 (name, comment, time, kagi) VALUES (:name, :comment, :time, :kagi)");
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	            $sql -> bindParam(':time', $time, PDO::PARAM_STR);
	            $sql -> bindParam(':kagi', $kagi, PDO::PARAM_STR);
            	$sql -> execute();
          }
       }
    }
    
    ?>
     <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edit_name)) {echo $edit_name;} ?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edit_comment)) {echo $edit_comment;} ?>">
      <button type="submit" name="send" value="insert">送信する</button>
      <input type="text" name="num_delete" placeholder="削除対象番号">
      <button type="submit" name="delete" value="insert">削除</button>
      <input type="text" name="num_edit" placeholder="編集対象番号" >
      <button type="submit" name="edit" value="insert">編集</button>
      <input type="hidden" name="num_flag"  value="<?php if(!empty($edit_number)) {echo $edit_number;} ?>">
      <input type="text" name="kagi" placeholder="パスワード" >
    </form>
    <?php
    $sql = 'SELECT * FROM board2 ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
	echo "<hr>";
	}
    ?>
</body>
</html>