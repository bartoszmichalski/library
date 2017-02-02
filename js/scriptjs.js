
jQuery(function(){
    var book1;
    jQuery.ajax({
                url:'http://localhost/library/src/rest.php',
                method: 'GET'
//                ,
//                data: {id:1}
                }) 
           .done(function(response){
                       //console.log(response);
                var UL = jQuery('ul'); 
                var books = JSON.parse(response);
//               var booksarray=[];
 //              booksarray = Array.from(books); 
//               console.log(booksarray.length);
   //                 if (books.length>1) {
                        books.forEach(function(book){
                        var part1='<li id=\"';
                        var part2=book.id;
                        var part3='\"><a href="#\">';
                        var part4=book.title;
                        var part5='</a> ( ';
                        var part6=book.author;
                        var part7=' ) <form class="delBook" id="'+book.id+'"><button type=\"submit\" name=\"submit1\" value=\"usuń\">Usuń</button></form><div><form class=\"editBook\" id="'+book.id+'" method=\"PUT\" action=\"http://localhost/library/src/rest.php\"><label>Tytuł:</label><br><input name=\"title\" type=\"text\" maxlength=\"255\" value=\"\" size=\"100\"/><br><label>Autor:</label><br><input name=\"author\" type=\"text\" maxlength=\"255\" value=\"\" size=\"100\"/><br><input type="hidden" name=\"id" value="'+book.id+'"><button id="'+book.id+'" type=\"submit\" name=\"save\" value=\"save\">Zapisz</button><br></div></li>';
                        var newLI = jQuery(part1+part2+part3+part4+part5+part6+part7);  
                        newLI.appendTo(UL);
                    });
                            jQuery('a').on('click', function (event) {
                                event.preventDefault();
//                              console.log(jQuery(this).next().show());
                                jQuery('a').next().next().hide()
                                jQuery(this).next().next().show();
                                var id = jQuery(this).parent().attr('id');
                                //var book1;
                                jQuery.ajax({
                                    url:'http://localhost/library/src/rest.php',
                                    method: 'GET',
                                    data: {id}
                                    }) 
                                    .done(function(response){
                                   var book1 = JSON.parse(response);
                                //  console.log(jQuery('title_e'));
                                   var editBookID='#'+id+'.editBook ';
                                   var title = jQuery(editBookID).children().eq(2);//.next().next()//.children().eq(0).children().eq(1);
                                   console.log(title);
                                //                      jQuery('input[name="title_e"]');
                                   title.val(book1.title);
                                   var author = jQuery(editBookID).children().eq(6);
                                   author.val(book1.author);
                                   console.log(jQuery('button#'+id));
                        var datastringtest=jQuery(editBookID).serialize()
                        console.log(datastringtest);
                                   jQuery('button#'+id).on('click', function (event) {
                                       event.preventDefault(); 
                                       event.stopImmediatePropagation();
//                                       var newTitle = title.val();
//                                        var newAuthor = author.val();
//                                       
                                        var datastring=jQuery(editBookID).serialize()
                                                //'id='+id+'&title="'+newTitle+'"&author="'+newAuthor+'"';
//                                        var datastring1='id='+id+'&title=""&author=""';
                                           console.log(datastring);
                                         //  setTimeout(function () {1+1}, 10000)
//                                        var datajson = JSON.stringify({ 'id': id, 'title': newTitle, 'author': newAuthor });
//                                        console.log(datajson);
                                         jQuery.ajax({
                                            url:'http://localhost/library/src/rest.php',//?'+datastring1,
                                            method: 'PUT',
                                            data: datastring
                                            //data: {'id':id,'title':newTitle,'author':newAuthor}
//                                        data: datajson
                                        }) 
                                        .done(function(response){
                                   //var book1 = JSON.parse(response);
                                        });
                                    });
                                  // console.log(jQuery(this).next().next().children().eq(0).children().eq(2).val(book1.title));
//                                        book1.id;
//                                   book1.title;
                                   
                                });                 
                            //var book1 = click(id);
                            //console.log(book1);
                        });
//                            jQuery('.delBook').on('click', function (event) {
//                                jQuery(this)
//                            });
                    
  //                     };
                        
                        
            });
    function click (id){
   //    var book1;
        jQuery.ajax({
                url:'http://localhost/library/src/rest.php',
                method: 'GET',
                data: {id}
                }) 
                .done(function(response){
               var book1 = JSON.parse(response);
              console.log(book1);
               return book1;
           });
    console.log(book1);       
    return book1;   
       
    };
});