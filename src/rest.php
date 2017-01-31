<?php
//include_once 'connection.php';
require_once './config.php';

class Book implements JsonSerializable {
    public $id;
    public $title;
    public $author;

    public $books= [];
    
    public function __construct() {
        $this->id = -1;
        $this->author='';
        $this->title='';
    }
    
    public function getId () {
        return $this->id;
    }
    public function getTitle () {
        return $this->title;
    }
    public function getAuthor () {
        return $this->author;
    }
    public function setTitle ($title) {
        $this->title=$title;
        return $this;
    }
    public function setAuthor ($author) {
        $this->author=$author;
        return $this;
    }
    //Funkcję loadFromDB(conn, id).
    static function loadBookFromDBById($connection, $id) {
       $sql=sprintf("SELECT `id`, `title`, `author` FROM `books` WHERE id=%d", $id);
       
        $result =$connection->query($sql);
        if ($result==true && $result->num_rows ==1) {
        //   foreach($result as $row) {
                $row =$result->fetch_assoc();
                $loadedBook = new Book();
                $loadedBook->id = $row['id'];
                $loadedBook->title=$row['title'];
                $loadedBook->author=$row['author'];
                
             //   $ret[]=$loadedTweet;
                return $loadedBook;
           // }
        }
        return null;
        
    }
    public function saveBookInDB(mysqli $connection)
    {
        if ($this->id==-1) {
            $sql=  sprintf("INSERT INTO `books`(`id`, `title`, `author`) VALUES (NULL,'%s','%s')",
                            $this->title,
                            $this->author);
            $result = $connection->query($sql);
            if ($result==true) {
                $this->id=$connection->insert_id;
                return TRUE;
            }
        } else {
            $sql= sprintf("UPDATE `books` SET `title`='%s',`author`='%s' WHERE id=%d", $this->title, $this->author, $this->id);
            $result = $connection->query($sql);
            if($result == true){
            return true;
            }
        }
        return false;
    }
    public function deleteBookFromDB(mysqli $connection){
        if($this->id != -1){
            $sql = sprintf("DELETE FROM `books` WHERE id=",$this->id);
            $result = $connection->query($sql);
            if($result == true){
                $this->id = -1;
                return true;
            }
        return false;

        }
 
        return true;
    }
    static function loadAllBooks(mysqli $connection) {
        $sql="SELECT * FROM books";
        $ret = [];
        $result =$connection->query($sql);
        if ($result == true && $result->num_rows!=0) {
            foreach($result as $row) {
                $loadedBook = new Book ();
                $loadedBook->id = $row['id'];
                $loadedBook->title=$row['title'];
                $loadedBook->author=$row['author'];
                $books[]=$loadedBook;
            }
        }
      // var_dump($books);
        return $books;
        
    }
    public function jsonSerialize() {
        if(count($this->books)){
            $books = [];
            foreach($this->books as $book){
                $books[]=$this->getBookAsTable($book);
            }
            return $books;
        }else{
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







//header('Content-Type:application/json; charset=UTF-8');

switch($_SERVER['REQUEST_METHOD']){
    
    case 'POST':
         //   echo 'DODAWANIE POZYCJI';
        if (isset($_POST['title']) && isset($_POST['author'])) {
            $title=$_POST['title'];
            $author=$_POST['author'];
            $newBook = new Book();
            $newBook->setTitle($title);
            $newBook->setAuthor($author);
            if ($newBook->saveBookInDB($mysql)) {
            echo json_encode('OK');
            header("Location: http://localhost/library/index.html");
            }
        }
        break;
    
    case 'GET':
            if(isset($_GET['id'])){   ///wczytywanie książki o podanym id
               
            //$book = new Book();
            //$book->getId($_GET['id']);
            $loadedBook=Book::loadBookFromDBById($mysql,$_GET['id']);
            echo json_encode($loadedBook);
               
          }else{
              
           $books = Book::loadAllBooks($mysql);
           echo (json_encode($books));
           }
        break;
    
    case 'PUT':
            echo 'EDYCJA';
            parse_str(file_get_contents("php://input"), $put_vars);
//            $bookPut= json_parse($put_vars);
            if (isset($put_vars['id']) && $put_vars['title']!='' && $put_vars['author']!=''){
            $id=$put_vars['id'];
            $loadedbook = loadBookFromDBById ($mysql,$id);
            $loadedbook->setTitle($put_vars['title']);
            $loadedbook->setAuthor($put_vars['author']);
            if ($loadedbook->saveBookInDB($mysql)) {
                echo json_encode('OK');
                header("Location: http://localhost/library/index.html");
            
            }
            
             
                
            }
        break;
    
    
    case 'DELETE':
            echo 'KASOWANIE';
        break;
    
}

//
//class BOOK1{
//    public function getData(){
//        
//        $data=$_GET;
//      
//        $books = [ 
//                    ['id'=>$data['id'], 'title'=>'Winetou 1', 'author'=>'Mark Twain'],
//                    ['id'=>$data['id'], 'title'=>'Winetou 2', 'author'=>'Mark Twain'],
//                    ['id'=>$data['id'], 'title'=>'Winetou 3', 'author'=>'Mark Twain'],
//                    ['id'=>$data['id'], 'title'=>'Winetou 4', 'author'=>'Mark Twain'],
//                 ];
//        return $books;
//    }
//    
//}
//



