<?php
include 'config.php';

$tsql = "select mtd_start_date, mtd_end_date, mtd_bidclose_date, mtd_tender_no from  mms_tender_details where mtd_status = 'A'";
$stmt = mysqli_query($con, $tsql);
if ($stmt === false) {
    echo "Error in query";
    die(print_r(mysqli_error($con), true));
}
$index = 0;
while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC)) {

    // echo $row['mtd_start_date'];
    // echo $row['mtd_end_date'];

    $stardate = $row['mtd_start_date'];
    $enddate = $row['mtd_end_date'];
    $tNumber = $row['mtd_tender_no'];
    $bidclose_date = $row['mtd_bidclose_date'];

    $startdateConvert = date("M d Y", strtotime($stardate));
    $closedateConvert = date("M d Y g:i A", strtotime($bidclose_date));
}
// echo "sssssssssssssssssssssssssssssssssssssssss";
?>

<script>
    // Set the date we're counting down to
    var countDownDate = new Date("<?php echo $closedateConvert; ?>");
    // var countDownDate = new Date("Jun 31 2022").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {
        // Get today's date and time
        var nowDate = new Date();
        var options = {
            timeZone: 'Asia/Colombo',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        var formattedNowDate = new Intl.DateTimeFormat('en-US', options).format(nowDate);


        // var now = new Date("<?php echo $startdateConvert; ?>").getTime();

        // Find the distance between now and the count down date
        // var distance = countDownDate - nowdate;

        // var now = new Date().getTime();
        // var start = new Date("<?php echo $startdateConvert; ?>").getTime();

        // if (now == nowdate) {
        //     // Find the distance between now and the count down date
        //     var distance = countDownDate - nowdate;
        // }
        // else 
        // var distance = countDownDate - nowdate;
        var distance = countDownDate - nowDate;
        console.log(countDownDate);
        console.log(nowDate);
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("TimeCounter").innerHTML =
            days + " Days " + hours + "h " + minutes + "m " + seconds + "s ";
        //document.getElementById("TimeCounter").innerHTML = days + " Days left";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("TimeCounter").innerHTML = "TENDER CLOSED! ";
            document.getElementById("btnDoneFunc").disabled = true;
            document.getElementById("btndonemodal").style.background = "#A9A9A9";
            document.getElementById("btndonemodal").disabled = true;
            document.getElementById("btnDoneFunc").style.background = "#A9A9A9";
            document
                .getElementById("btndonemodal")
                .setAttribute("title", "No Opened Tender to submit! Please wait!");
            document
                .getElementById("btnDoneFunc")
                .setAttribute("title", "No Opened Tender to submit! Please wait!");
        }
    }, 1000);

    document.addEventListener("contextmenu", function(e) {
        e.preventDefault();
    });

    $(window).scroll(function() {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            // ajax call get data from server and append to the div
            var _img = document.getElementById("id1");
            var newImg = new Image();
            newImg.onload = function() {
                _img.src = this.src;
            };
        }
    });
</script>