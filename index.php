<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $value = $_POST['value'];
    $nir = $_POST['nir'];
    $msisdnType = $_POST['msisdnType'];

    $data = "value=$value&nir=$nir&msisdnType=$msisdnType";

    $url = "https://360.altanredes.com/operations/call/cambioMSISDN?$data";

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $cookie = "XSRF-TOKEN=eyJpdiI6IlVlOENDNHg1ellvaE1vVkkxTk9cL1Z3PT0iLCJ2YWx1ZSI6IkxLdU1jWVhqbFFud2QrbENqSmN6SHVHNUtGazBlU3NHZUwxbHdaWkhiUDJvQ1NBWFd4UWpjMWNFZUFNRklPckkiLCJtYWMiOiI2Yzk3NzZmMWI1N2VlZWQ0ZTE2NzliNWZmOWNkNzEwZTMxYTAyOWU1ZTE5N2M0YzAxOWM0MzA1MzgzOGU5NTkwIn0%3D; laravel_session=eyJpdiI6ImtDZk9teU9JRDg3QU9nXC82eW40cmhRPT0iLCJ2YWx1ZSI6IkI0MlV1aFk3VFVuNEp0c2FwSDB6MmZIY2dkaWlcL0Q5UzdqQ3l4VnI0ZjJRUWk2K05hcGNwT0U4QXB1ajI0MkNTIiwibWFjIjoiYWZjYWZmYWY0MDEyYzUzNTM4ZGJhMDk0ZGJjYWM0YWEyY2UwZjFiNTg1NGQ2MThkYWZjNWJlNWY3NDM0NTkwYyJ9; AWSALB=LnoF5u/H5qpT0bkgWLB7VFNxhLuP+M7RXrCkcvqCjCcxBggDOvfR1GHg3g+SgJ0zzotNaepIi2wO86vczjLUHjHIoMCCK3cD/uzilkQ4j8dgl9HxvxdYLNZ9mp2G; AWSALBCORS=LnoF5u/H5qpT0bkgWLB7VFNxhLuP+M7RXrCkcvqCjCcxBggDOvfR1GHg3g+SgJ0zzotNaepIi2wO86vczjLUHjHIoMCCK3cD/uzilkQ4j8dgl9HxvxdYLNZ9mp2G";

    $headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        "Cookie: $cookie"
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $resp = curl_exec($curl);
    curl_close($curl);
    echo($resp);
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    </head>
    <style>
        body {
            margin: 0px;
        }

        .container {
            display: flex;
            text-align: center;
            align-items: center;
            /* height: 100vh; */
            flex-direction: column;
        }

        form {
            margin: auto;
        }

        form>div {
            margin: 10px 0px;
        }

        form input[type="text"] {
            width: 300px;
            height: 30px;
        }

        form input[type="submit"],
        form input[type="button"] {
            width: 70px;
            height: 30px;
        }
    </style>

    <body>
        <div class="container" style="text-align: center;">
            <form method="get" id="myForm">
                <div>
                    <input type="text" name="value" id="textbox1" value="5500000000" placeholder="value">
                </div>
                <div>
                    <input type="text" name="nir" id="textbox2" value="123" placeholder="nir">
                    <input type="hidden" name="msisdnType" value="1">
                </div>
                <div>
                    <input type="submit" id="send" value="Send">
                    <input type="button" value="Reset" id="reset">
                </div>
            </form>
            <div>
                <h3>The result:</h3>
                <div id="result"></div>
            </div>
        </div>
    </body>
    <script>
        // get form data as URLSearchParams object
        $(document).ready(function() {

            $('#reset').on('click', function() {
                $('#textbox1').val('');
                $('#result').html('');
            })

            // attach a submit event handler to the form
            $("#myForm").submit(function(event) {
                // prevent the default form submission
                event.preventDefault();

                // get the form data as a URL-encoded string
                const formData = $(this).serialize();

                // send an AJAX request to the server with headers
                $.ajax({
                        url: "/",
                        type: "POST",
                        crossDomain: true,
                        data: formData,
                    })
                    .done(function(response) {
                        console.log("----------------------", response);
                        $('#result').html(response);
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.error(errorThrown);
                    });
            });

        });
    </script>

    </html>
<?php
}
?>
