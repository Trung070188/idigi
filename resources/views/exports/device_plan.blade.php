<h3>Danh sách thiết bị cần cài đặt</h3>
<br>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th style="border: 1px solid black">No</th>
        <th style="border: 1px solid black">Device name</th>
        <td style="border: 1px solid black">Register code</td>
        <td style="border: 1px solid black">Expire date</td>
        <td style="border: 1px solid black">Confirmation code</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td style="border: 1px solid black">{{$key+1}}</td>
            <td style="border: 1px solid black">{{$item['device_name']}}</td>
            <td style="border: 1px solid black">{{$item['device_uid']}}</td>
            <td style="border: 1px solid black">{{$item['expire_date']}}</td>
            <td style="border: 1px solid black">{{$item['code']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
