
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th>Device Name</th>
        <td>Register code</td>
        <td>School Name</td>
        <td>Confirmation code</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
        <tr>

            <td>{{$item['device_name']}}</td>
            <td>{{$item['device_uid']}}</td>
            <td>{{$item['school_name']}}</td>
            <td>{{$item['code']}}</td>
{{--            <td>{{$item['confirmation code']}}</td>--}}
        </tr>
    @endforeach

    </tbody>
</table>
