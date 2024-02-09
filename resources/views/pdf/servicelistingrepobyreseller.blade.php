<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Listing Report By Reseller</title>
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
<h1>Service Listing Report By Reseller</h1>

@if(empty($service_listing_repo_by_reseller))
    <p>No sim cards available.</p>
@else
    <table border="1">
        <thead>
        <tr>
           
            <th>Status</th>
            <th>Order ID</th>
            {{-- <th>Account</th> 
            <th>Site</th>  --}}
            <th>Order Date</th>
            <th>Sim Card</th> 
            <th>Mobile Number</th> 
            {{-- <th>Plan</th>  --}}
            <th>End User</th> 
        </tr>
        </thead>
        <tbody>
           
        @foreach($service_listing_repo_by_reseller as $service_listing)
      
            <tr>
                <td>{{ $service_listing['service_status'] }}</td>
                <td>{{ $service_listing['order_id'] }}</td>
                <td>{{ $service_listing['created_at'] }}</td>
                <td>{{ $service_listing['sim_card_code'] }}</td>
                <td>{{ $service_listing['mobile_number'] }}</td>
                <td>{{ $service_listing['end_user_name'] }}</td>
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
