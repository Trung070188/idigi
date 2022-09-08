
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th>Device Name</th>
        <td>Type</td>
        <td>Register code</td>
        <td>Status</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
        <tr>

            <td>{{$item['device_name']}}</td>
            <td>{{$item['type']}}</td>
            <td>{{$item['device_uid']}}</td>
            <td>{{$item['status']}}</td>
            <td>@if(@$item['error'])
                    @foreach($item['error'] as $er)
                        @foreach($er as $value)
                            {{$value}}<br>
                        @endforeach

                    @endforeach
            </td>
            @endif


        </tr>
    @endforeach

    </tbody>
</table>
