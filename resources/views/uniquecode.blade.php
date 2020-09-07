<!DOCTYPE html>

<html>

<head>

    <title>Generate Unique Number</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body>

  

    <div class="container">

        <h1>Generate Unique Number</h1>
        <form >
            <div class="form-group">
                <label>Number:</label>
                <input type="text" name="number" class="form-control" placeholder="Your Number" required="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" >
            </div>

            <div class="form-group">
                <label id="minutes">00</label>:<label id="seconds">00</label>
            </div>
            <div class="form-group">
                <label id="message">-</label>
            </div>
   
            <div class="form-group">
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </form>
    </div>

  

</body>

<script type="text/javascript">
    let intervalId = null;
    var minutesLabel = document.getElementById("minutes");
    var secondsLabel = document.getElementById("seconds");
    var totalSeconds = 0;
    var messageLabel = document.getElementById("message");

    function setTime() {
        ++totalSeconds;
        secondsLabel.innerHTML = pad(totalSeconds % 60);
        minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
    }

    function pad(val) {
        var valString = val + "";
        if (valString.length < 2) {
            return "0" + valString;
        } else {
            return valString;
        }
    }
   

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   

    $(".btn-submit").click(function(e){
        e.preventDefault();

        var number = $("input[name=number]").val();

        if (number == "") {
            messageLabel.innerHTML = "Please Insert Your Number";
            return;
        }
        intervalId = setInterval(setTime, 1000);
        // alert(number);

        $.ajax({
            type:'POST',
            url:'/uniquecode/store',
            data:{count:number},
            success:function(data){
                // alert(data);
                if (intervalId){
                    clearInterval(intervalId);
                }
                console.log(data);
                messageLabel.innerHTML = data;
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var error = xhr.responseText ;
                messageLabel.innerHTML = error; 
                if (intervalId){
                    clearInterval(intervalId);
                }
                console.log( xhr.status);
            }
        });
    });

</script>
</html>