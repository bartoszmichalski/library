
jQuery(function(){
    var book1;
    //get all books
    jQuery.ajax({
    url:'http://localhost/library/src/rest.php',
            method: 'GET'
    })
    .done(function(response){
        var books = JSON.parse(response);
        printTable (books);
        //get book
        jQuery('body').on( 'click', 'a', function (event) {
            jQuery('a').next().next().hide()
            jQuery(this).next().next().show();
            var id = jQuery(this).parent().attr('id');
            jQuery.ajax({
                url:'http://localhost/library/src/rest.php',
                method: 'GET',
                data: {id}
            })
            .done(function(response){
                var book1 = JSON.parse(response);
                var editBookID = '#book' + id + 'form';
                var title = jQuery(editBookID).children().eq(2);
                //     console.log('title:'+title);
                title.val(book1.title);
                var author = jQuery(editBookID).children().eq(6);
                author.val(book1.author);
                //     console.log(jQuery('button#'+id));
                var datastringtest = jQuery(editBookID).serialize()
            });
            //edit book
            jQuery('#book' + id + 'form').on('submit', function (event) {
                event.preventDefault();
                event.stopImmediatePropagation();
                var datastring = jQuery(this).serialize()
                jQuery.ajax({
                    url:'http://localhost/library/src/rest.php',
                    method: 'PUT',
                    data: datastring
                })
                .done(function(response){
                    var books = JSON.parse(response);
                    printTable (books);
                    var info = jQuery('.info');
                    info.text('Pozycja została zapisana');
                });
            });
        });
        //delete book
        jQuery('body').on('submit', '.delbook', function (event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            var id = jQuery(this).parent().attr('id');
           // console.log('id:' + id);
            jQuery.ajax({
                url:'http://localhost/library/src/rest.php',
                method: 'DELETE',
                data: {id}
            })
            .done(function(response){
                var books = JSON.parse(response);
                printTable (books);
                var info = jQuery('.info');
                info.text('Pozycja została usunięta');
            });
        });
        jQuery('.newBook').on('submit', function (event) {
            event.preventDefault(); 
            event.stopImmediatePropagation();
            var datastring=jQuery(this).serialize()
            jQuery.ajax({
                url:'http://localhost/library/src/rest.php',
                method: 'POST',
                data: datastring
            }) 
            .done(function(response){
                var books = JSON.parse(response);
                printTable (books);
                var info = jQuery('.info');
                info.text('Pozycja została dodana');
            });

        });        
    });
    function printTable (books) {
    var UL = jQuery('ul');
        UL.children().remove();
        if (books != null ) {
            books.forEach(function(book){
                var part1 = '<li id=\"';
                var part2 = book.id;
                var part3 = '\"><a href="#\">';
                var part4 = book.title;
                var part5 = '</a> ( ';
                var part6 = book.author;
                var part7 = ' ) <form class="delbook" id="delbook' + book.id + '" action="#" method="DELETE"><button type=\"submit\" name=\"submit1\" value=\"usuń\">Usuń</button></form><div><form class=\"editBook\" id="book' + book.id + 'form" method=\"PUT\" action=\"#\"><label>Tytuł:</label><br><input name=\"title\" type=\"text\" maxlength=\"255\" value=\"\" size=\"100\"/><br><label>Autor:</label><br><input name=\"author\" type=\"text\" maxlength=\"255\" value=\"\" size=\"100\"/><br><input type="hidden" name=\"id" value="' + book.id + '"><button id="book' + book.id + '" type=\"submit\" name=\"save\" value=\"save\">Zapisz</button><br></div></li>';
                var newLI = jQuery(part1 + part2 + part3 + part4 + part5 + part6 + part7);
                newLI.appendTo(UL);
            });
        }
    };
});