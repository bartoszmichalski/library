<?php
//include_once 'connection.php';
require_once 'config.php';

class Book implements JsonSerializable
{
    public $id;
    public $title;
    public $author;

    public $books = [];
    
    public function __construct()
    {
        $this->id = -1;
        $this->author ='';
        $this->title ='';
    }
    
    public function getId ()
    {
        return $this->id;
    }
    
    public function getTitle ()
    {
        return $this->title;
    }
    
    public function getAuthor ()
    {
        return $this->author;
    }
    
    public function setTitle ($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function setAuthor ($author)
    {
        $this->author = $author;
        return $this;
    }
    
    static function loadBookFromDBById($connection, $id)
    {
       $sql = sprintf("SELECT `id`, `title`, `author` FROM `books` WHERE id=%d", $id);
       
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedBook = new Book();
            $loadedBook->id = $row['id'];
            $loadedBook->title = $row['title'];
            $loadedBook->author = $row['author'];

            return $loadedBook;
        }
        
        return null;
    }
    
    public function saveBookInDB(mysqli $connection)
    {
        if ($this->id == -1) {
            $sql=  sprintf("INSERT INTO `books`(`id`, `title`, `author`) VALUES (NULL,'%s','%s')",
                            $this->title,
                            $this->author);
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return TRUE;
            }
        } else {
            $sql = sprintf("UPDATE `books` SET `title`='%s',`author`='%s' WHERE id=%d", $this->title, $this->author, $this->id);
            $result = $connection->query($sql);
            if($result == true){
                return true;
            }
        }
        
        return false;
    }
    
    public function deleteBookFromDB(mysqli $connection)
    {
        if($this->id != -1){
            $sql = sprintf("DELETE FROM `books` WHERE id=%d",$this->id);
            $result = $connection->query($sql);
            if($result == true){
                $this->id = -1;
                return true;
            }
            
            return false;
        }
 
        return true;
    }
    
    static function loadAllBooks(mysqli $connection)
    {
        $sql="SELECT * FROM books";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach($result as $row) {
                $loadedBook = new Book ();
                $loadedBook->id = $row['id'];
                $loadedBook->title = $row['title'];
                $loadedBook->author = $row['author'];
                $books[] = $loadedBook;
            }
            return $books;    
        }
      
        return null;        
    }
    
    public function jsonSerialize()
    {
        if(count($this->books)){
            $books = [];
            foreach($this->books as $book){
                $books[] = $this->getBookAsTable($book);
            }
            return $books;
        } else {
            return $this->getBookAsTable($this);            
        }
    }
    
    private function getBookAsTable($bookObj){
        return [
                'id'=>$bookObj->id,
                'title'=>$bookObj->title,
                'author'=> $bookObj->author
            ];
    }
}

switch($_SERVER['REQUEST_METHOD']){
    
    case 'POST':
         //   add action
        if (isset($_POST['title']) && isset($_POST['author']) && $_POST['title'] != '' && $_POST['author'] != '') {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $newBook = new Book();
            $newBook->setTitle($title);
            $newBook->setAuthor($author);
            if ($newBook->saveBookInDB($mysql)) {
                $books = Book::loadAllBooks($mysql);
                echo (json_encode($books));
            }
        } else {
            $books = Book::loadAllBooks($mysql);
                echo (json_encode($books));
        }
        break;
    
    case 'GET':
        // get action
        if(isset($_GET['id'])){   // load book by id
            $loadedBook = Book::loadBookFromDBById($mysql,$_GET['id']);
            echo json_encode($loadedBook);
        } else {
            $books = Book::loadAllBooks($mysql);
            echo (json_encode($books));
        }
        break;
    
    case 'PUT':
        // edit action;
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = $put_vars['id'];
        $loadedbook = Book::loadBookFromDBById ($mysql,$id);
        $loadedbook->setTitle($put_vars['title']);
        $loadedbook->setAuthor($put_vars['author']);
        if ($loadedbook->saveBookInDB($mysql)) {
            $books = Book::loadAllBooks($mysql);
            echo (json_encode($books));
        }
        break;
        
    case 'DELETE':
        //delete action;
        parse_str(file_get_contents("php://input"), $del_vars);
        $id = $del_vars['id'];
        $delbook = Book::loadBookFromDBById ($mysql,$id);
        if ($delbook->deleteBookFromDB($mysql)) {
            $books = Book::loadAllBooks($mysql);
            echo (json_encode($books));
        }
        break;    
}



