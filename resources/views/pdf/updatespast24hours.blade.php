<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last 24hr Changes To Mobile Services</title>
    <style>
        h1{
            align-content: center;
        }
        #currentDateTime {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<div id="currentDateTime"></div>
<img src="images/Buro-logo.png" alt="Buroserv Logo" width="200" height="100"/>
<h1>Last 24hr Changes To Mobile Services</h1>

@if(empty($mobile_update))
    <p>No sim cards available.</p>
@else
    <table border="1">
        <thead>
        <tr>
            <th>Mobile Number</th> 
            <th>SIM Card Number</th>
            <th>Package Name</th>
            <th>Reseller Name</th>
            <th>Reseller Billing Acc No</th> 
            <th>What Changes</th> 
           
        </tr>
        </thead>
        <tbody>
           
        @foreach($mobile_update as $update)
      
            <tr>
                <td>{{ $update['mobile_number'] }}</td>
                <td>{{ $update['sim_card_code'] }}</td>
                <td>{{ $update['package_name'] }}</td>
                <td>{{ $update['reseller_name'] }}</td>
                <td>{{ $update['reseller_billing_account_no'] }}</td>
                <td>{{ $update['what_change'] }}</td>
               
             </tr>
        @endforeach

       
        </tbody>
    </table>
@endif

<script>
    function updateCurrentDateTime() {
        var currentDateTimeElement = document.getElementById('currentDateTime');
        var currentDate = new Date();
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        var formattedDateTime = currentDate.toLocaleDateString('en-US', options);
        currentDateTimeElement.textContent = 'Date and Time: ' + formattedDateTime;
    }

    // Update the current date and time on page load
    window.onload = function () {
        updateCurrentDateTime();
    };
</script>

</body>
</html>
