<h3>Danh sách thiết bị cần cài đặt</h3>
<br>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th>No.</th>
        <th>Device name</th>
        <td>Expire date</td>
        <td>Status</td>

    </tr>
    </thead>

    <tbody>
    @foreach($data as $key => $item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item['device_name']}}</td>
            <td>{{$item['expire_date']}}</td>
            <td></td>
        </tr>
    @endforeach

    </tbody>
</table>
