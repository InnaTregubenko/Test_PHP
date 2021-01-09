<?php
function CreateDB($host = 'localhost', $user = 'root', $pass = '', $dbname = 'TestPHP'){
    $conn = @new mysqli($host, $user, $pass);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

// Create database
    $sql = "CREATE DATABASE ". $dbname ;
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }
    $conn->close();
    return true;
}
function connect($host = 'localhost', $user = 'root', $pass = '', $dbname = 'TestPHP')
{
    $mysqli = @new mysqli($host, $user, $pass, $dbname);
    if ($mysqli->connect_errno) {
        die('Error connection: ' . $mysqli->connect_errno);
    }
    $mysqli->query("set names 'utf8'");
    return $mysqli;
}

function CreateTables(){
    $flag = CreateDB();
    if($flag){
        $mysqli = connect();
        $ct1 = "create table users( id int not null auto_increment primary key, first_name varchar(32), last_name varchar(32), phone varchar(128), email varchar(128) unique)default charset='utf8'";
        $ct2 = "create table posts( id int not null auto_increment primary key, title varchar(32), body varchar(2048), userId int, foreign key(userId) references users(id) on delete cascade)default charset='utf8'";

        if (!$mysqli->query($ct1)) {
            printf("Errorcode 1: %d\n", $mysqli->errno);
            exit();
        }

        if (!$mysqli->query($ct2)) {
            printf("Errorcode 2: %d\n", $mysqli->errno);
            exit();
        }
        InsertIntoUsers();
        //$mysqli->close();
    }
}

function InsertIntoUsers(){
    $mysqli=connect();
    $ins = array (
    'insert into users (first_name,last_name,phone,email) values("ivan","ivanov","+3(099)0000000","ivan@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("petr","petrov","+3(099)0000000","petr@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("maxim","maximov","+3(099)0000000","maxim@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("sergey","sergeev","+3(099)0000000","sergey@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("kostya","kostyev","+3(099)0000000","kostya@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("nikita","nikitov","+3(099)0000000","nikita@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("egor","egorov","+3(099)0000000","egor@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("fedor","fedorov","+3(099)0000000","fedor@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("lev","levov","+3(099)0000000","lev@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("roman","romanov","+3(099)0000000","roman@gmail.com")',
        'insert into users (first_name,last_name,phone,email) values("nikolay","nikolaev","+3(099)0000000","nikolay@gmail.com")'
    );
    foreach ( $ins as $i => $value) {
        $mysqli->query($value);
    }
    return true;
}

function CheckTables(){
    $link_connect_to_db = mysqli_connect('localhost', 'root', '', 'TestPHP');
    $q = 'SELECT * FROM users LIMIT 1';
    $mysql_table_check = mysqli_query($link_connect_to_db, $q);
    if($mysql_table_check) { return true; } else { return false; }
}

?>
