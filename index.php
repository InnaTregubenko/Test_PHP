<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Agency</title>
    <script src="jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        h2,h3 {
            text-align: center;
        }

        #forbuttons div {
            width: 150px;
        }
        td:nth-child(even) {
            width: 70%;
        }

    </style>
</head>
<body>
<div id="main" class="container d-flex flex-column p-2 bg-info text-white w-50">
    <h2>All users:</h2>
    <button class="btn btn-light" id="filt">Filter by Name</button>
    <button class="btn btn-light" id="cancfilt" disabled>Cancel filter</button>

    <div id="forbuttons" class="container d-flex flex-row flex-wrap justify-content-between"></div>

    <h2>User info:</h2>
    <div id="fortable"></div>
    <br>

    <input type="submit" class="btn btn-light" value="Show posts"/>
    <br>
    <div id="forarticle" class="container d-flex flex-row flex-wrap justify-content-between"></div>

</div>
<script>
    async function doAjax(url, args) {
        let result = await $.getJSON(url);
        return result;
    }
//
    $(function () {
        doAjax(
            "http://jsonplaceholder.typicode.com/posts"
        ).then((data) => {
            let dataString = JSON.stringify(data);
            $.ajax({
                url: 'CreateDB.php',
                data: {myData: dataString},
                type: 'POST',
                dataType: 'json',
                success: function () {}
            });

            $.ajax({
                url: 'create.php',
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    let tempid = 0;
                    console.log(response);
                    for (let i = 0; i < response.length; i++) {
                        let user = new Users(response[i].id, response[i].first_name, response[i].last_name, response[i].phone, response[i].email);
                        arrayUsers.push(user);
                        $("#forbuttons").append(`<div class="container d-flex justify-content-center border border-secondary rounded p-2 m-2" data-toggle="tooltip" title="Click on me!" id="${response[i].id}">` + response[i].first_name + " " + response[i].last_name + '</div>');

                        $(document).on('click', `#${i + 1}`, function (e) {
                            //удалила предыдущую таблицу
                            $('table').remove();
                            $("#forarticle").empty();
                            $("#more").remove();
                            for (let i = 0; i < arrayUsers.length; i++) {
                                $(`#${i + 1}`).css("font-weight", "normal");
                            }
                            $(this).css("font-weight", "bold");

                            //рисую таблицу
                            let table = $('<table class="table table-dark table-hover w-100" id="tab"/>');
                            for (let i = 0; i < 3; i++) {
                                var row = $('<tr/>');
                                for (let j = 0; j < 2; j++) {
                                    var cell = $('<td/>');
                                    row.append(cell);
                                }
                                table.append(row);
                            }

                            //сначала добавила все строки ячейки в таблицу. а  потом уже по индексу записывать туда текст
                            table.appendTo('#fortable');

                            tempid = $(this).attr('id');

                            for (let i = 0; i < arrayUsers.length; i++) {
                                if (arrayUsers[i]["id"] == tempid) {
                                    $('td').eq(0).html("Name:");
                                    $('td').eq(1).html(arrayUsers[i]["name"] + " " + arrayUsers[i]["surname"]);
                                    //$("td:eq(1)").css("color", "red");
                                    $('td').eq(2).html("Phone:");
                                    $('td').eq(3).html(arrayUsers[i]["phone"]);
                                    $('td').eq(4).html("Email:");
                                    $('td').eq(5).html(arrayUsers[i]["email"]);
                                    break;
                                }
                            }
                        });

                    }

                    $("#filt").click(function () {
                        let arr = new Map();
                        $('#forbuttons > div').each(function () {
                            arr.set($(this).attr("id"), $(this).html());
                        });
                        let temp = [];
                        for (let value of arr.values()) {
                            temp.push(value);
                        }
                        temp = temp.sort();
                        let results = new Map();
                        for (let x = 0; x < temp.length; x++) {
                            for (let pair of arr.entries()) {
                                if (pair[1] == temp[x]) {
                                    results.set(pair[0], pair[1]);
                                }
                            }
                        }
                        $('#forbuttons').empty();
                        results.forEach(function (item, i, arr) {
                            $("#forbuttons").append(`<div class="container d-flex justify-content-center border border-secondary rounded p-2 m-2"  data-toggle="tooltip" title="Click on me!"  id="${i}">` + item + '</div>');
                        });
                        $("#cancfilt").prop('disabled', false);
                        $("#filt").prop('disabled', true);
                    });

                    $("#cancfilt").click(function () {
                        $('#forbuttons').empty();
                        for (let i = 0; i < arrayUsers.length; i++) {
                            $("#forbuttons").append(`<div class="container d-flex justify-content-center border border-secondary rounded p-2 m-2"  data-toggle="tooltip" title="Click on me!"  id="${arrayUsers[i].id}">` + arrayUsers[i].name + " " + arrayUsers[i].surname + '</div>');
                        }
                        $("#cancfilt").prop('disabled', true);
                        $("#filt").prop('disabled', false);
                    });


                    $("input").click(function () {
                        doAjax(
                            `PostInfo.php?userId=${tempid}`
                        ).then((data) => {
                            console.log(data);
                            $('h3').remove();
                            let h2 = $('<h3>User\'s posts:</h3>');
                            h2.insertAfter($("input"));
                            let elements = document.querySelectorAll('p');
                            for (let i = 0; i < elements.length; i++) {
                                $('h4').remove();
                                $('p').remove();
                                $('#article').remove();
                            }
                            $("#more").remove();
                            for (let i = 0; i < data.length; i++) {
                                let articlediv = $('<div id="article" class="container d-flex flex-column justify-content-center border border-secondary rounded p-2 w-50"/>');
                                let h4 = $('<h4/>');
                                let p = $('<p/>');
                                h4.html(data[i].title);
                                p.html(data[i].body);
                                articlediv.append(h4);
                                articlediv.append(p);
                                $("#forarticle").append(articlediv);
                            }
                            let articlediv = $('<button class="btn btn-light" id="more">More posts</button>');

                            $("#main").append(articlediv);
                            $("#more").click(function () {
                                doAjax(
                                    `MorePost.php?userId=${tempid}`
                                ).then((data) => {
                                    $("#more").css("display", "none");
                                    console.log(data);
                                    for (let i = 0; i < data.length; i++) {
                                        let articlediv = $('<div id="article" class="container d-flex flex-column justify-content-center border border-secondary rounded p-2 w-50"/>');
                                        let h4 = $('<h4/>');
                                        let p = $('<p/>');
                                        h4.html(data[i].title);
                                        p.html(data[i].body);
                                        articlediv.append(h4);
                                        articlediv.append(p);
                                        $("#forarticle").append(articlediv);
                                    }
                                });
                            });
                        });


                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        });
    });


    class Users {
        constructor(id, name, surname, phone, email) {
            this.name = name;
            this.id = id;
            this.surname = surname;
            this.email = email;
            this.phone = phone;
        }
    }

    let arrayUsers = new Array();

</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
</body>
</html>
