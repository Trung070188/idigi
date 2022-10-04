<h3>Danh sách thiết bị cần cài đặt</h3>
<br>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <td>Status</td>
        <th>No</th>
        <th>Device name</th>
        <td>Register code</td>
        <td>Expire date</td>
        <td>Confirmation code</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td><input type="checkbox" value="1"/></td>
            <td>{{$key+1}}</td>
            <td>{{$item['device_name']}}</td>
            <td>{{$item['device_uid']}}</td>
            <td>{{$item['expire_date']}}</td>
            <td>{{$item['code']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
