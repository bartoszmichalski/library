<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'src/connection.php';
include_once 'src/rest.php';
include_once 'src/config.php';



//var_dump(Book::loadAllBooks($mysql));
//$books = Book::loadAllBooks($mysql);
//$books->jsonSerialize();
//var_dump(json_encode($books));

$book = Book::loadBookFromDBById($mysql, 1);
var_dump($book);

$book->deleteBookFromDB($mysql);
     
            


var_dump($book);
$book = Book::loadBookFromDBById($mysql, 1);
var_dump($book);
        ?>