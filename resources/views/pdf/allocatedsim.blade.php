<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocated Sim Report</title>
    <style>
        h2 {
            text-align: center;
        }

        #currentDateTime {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid rgb(32, 32, 32)818;
            padding: 8px;
            text-align: left;
        }
    </style>

<script>
    var now = new Date();
    var datetime = now.toLocaleString();
    document.getElementById("datetime").innerHTML = datetime;
</script>

</head>
<body>

<div id="datetime"></div>
<img src="images/Buro-logo.png" alt="Buroserv Logo" width="100" height="50" align = "center"/> 
<br>
<h2>Allocated Sim Report</h2>

@if(empty($allocated_sim_by_reseller))

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

        @foreach($allocated_sim_by_reseller as $allocated_by_reseller)

            <tr>
                <td>{{ $allocated_by_reseller['shipvia_agent_name'] }}</td>
                <td>{{ $allocated_by_reseller['sim_card_code'] }}</td>
                <td>{{ $allocated_by_reseller['created_at'] }}</td>
                <td>{{ $allocated_by_reseller['status'] }}</td>
                <td>{{ $allocated_by_reseller['mobile_number'] }}</td>
                {{-- <td>{{ $allocated_by_reseller['reseller_inventory'] }}</td> --}}
               
            </tr>
        @endforeach


        </tbody>
    </table>
@endif



</body>
</html>
