<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile services to get billed in the last 24hrs</title>
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
<h1>Mobile Services Report</h1>

@if(empty($mobile_service))
    <p>No mobile services data available.</p>
@else
    <table border="1">
        <thead>
        <tr>
            <th>Mobile Service ID</th>
            <th>Mobile Number</th>
            <th>Cost Centre</th>
            <th>SIM Card Code</th>
            <th>Reseller Name</th>
            <th>Reseller Billing Account No</th>
            <th>Package Id</th>
            <th>Customer Name</th>
            <th>Customer Account</th>

        </tr>
        </thead>
        <tbody>
           
        @foreach($mobile_service as $mobile)
      
            <tr>
                <td>{{ $mobile['mobile_service_id'] }}</td>
                <td>{{ $mobile['mobile_number'] }}</td>
                <td>{{ $mobile['cost_centre'] }}</td>
                <td>{{ $mobile['sim_card_code'] }}</td>
                <td>{{ $mobile['reseller_name'] }}</td>
                <td>{{ $mobile['reseller_billing_account_no'] }}</td>
                <td>{{ $mobile['package_id'] }}</td>
                <td>{{ $mobile['primary_contact_name'] }}</td>

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
