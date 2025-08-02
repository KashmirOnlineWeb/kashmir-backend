<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <h2>Hi {{$data['name']}},</h2>
    <p>{{ $data['message'] }}</p>
    <p>
        <table>
            <tr>
                <td>Hotel name:</td><td>{{$data['booking']['hotel_name']}}</td>
            </tr>
            <tr>    
                <td>Start date:</td><td>{{$data['booking']['start_date']}}</td>
            </tr>
            <tr>    
                <td>End date:</td><td>{{$data['booking']['end_date']}}</td>
            </tr>
            <tr>    
                <td>No. of Adults:</td><td>{{$data['booking']['adults']}}</td>
            </tr>
            <tr>    
                <td>No. of Children:</td><td>{{$data['booking']['children']}}</td>
            </tr>
            <tr>    
                <td>Amount:</td><td>{{$data['booking']['amount']}}</td>
            </tr>
            <tr>    
                <td>Status:</td><td>{{$data['booking']['status']}}</td>
            </tr>
        </table>
    </p>
  </body>
</html>