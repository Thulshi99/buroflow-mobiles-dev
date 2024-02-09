<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unallocated Sim Report</title>
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
<h1>Unallocated Sim Report</h1>

@if(empty($unallocated_sim_by_reseller))
    <p>No sim cards available.</p>
@else
    <table border="1">
        <thead>
        <tr>
            <th>Carrier</th> 
            <th>SIM Card Number</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Mobile Number</th> 
            <th>Inventory</th> 
           
        </tr>
        </thead>
        <tbody>
           
        @foreach($unallocated_sim_by_reseller as $unallocated_by_reseller)
      
            <tr>
                <td>{{ $unallocated_by_reseller['shipvia_agent_name'] }}</td>
                <td>{{ $unallocated_by_reseller['sim_card_code'] }}</td>
                <td>{{ $unallocated_by_reseller['created_at'] }}</td>
                <td>{{ $unallocated_by_reseller['status'] }}</td>
                <td>{{ $unallocated_by_reseller['mobile_number'] }}</td>
                <td>{{ $unallocated_by_reseller['reseller_inventory'] }}</td>
               
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
