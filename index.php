<?php
$con = mysqli_connect('localhost','root','123','netnote');
$user_ip = $_SERVER['REMOTE_HOST'];
//echo "<pre>";
//var_dump($_SERVER);
//die($user_ip);
if (isset($_GET['installation'])){
    mysqli_query($con,'CREATE TABLE `netnote`.`contents` ( `id` INT(5) NOT NULL AUTO_INCREMENT , `user_ip` VARCHAR (40) NOT NULL , `content` VARCHAR(500) NOT NULL , `last_updated` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;');
    exit;
}

if(!$con){
    echo mysqli_error($con);
    exit;
}

function getdata($con,$ip){
    $data = mysqli_query($con,'select * from contents where user_ip = "'.$ip.'"');
    if (mysqli_num_rows($data)>0){
        while ($row = mysqli_fetch_assoc($data)){
            $res = [true,$row['content']];
            return $res;
        }
    }
    return $res = [false];;
}
if (isset($_POST['save'])){
    $user_content = $_POST['content'];
    $query = 'update contents set content = "'.$user_content.'" where user_ip = "'.$user_ip.'"';
    $content = getdata($con,$user_ip);
    if(!$content[0]){
        $query = 'insert into contents (`id`,`user_ip`,`content`) values(NULL,"'.$user_ip.'","'.$user_content.'") ';
    }
    $saveContent = mysqli_query($con,$query);
    header('Location:'.$_SERVER['DOCUMENT_URI']);
}
$content = getdata($con,$user_ip)[0]==true?getdata($con,$user_ip)[1]:'';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Net Notepad</title>
</head>
<body>
    <h1>Share data from devices on same network</h1>
    <div class="container">
        <form action="#" method="post">
            <div class="form-group">
                <div class="group-item">
                    <textarea name="content" id="" cols="30" rows="10" value="<?= $content ?>" required><?= $content ?></textarea>
                </div>
            </div>
            <button name="save">Submit</button>
        </form>
    </div>
</body>
</html>
