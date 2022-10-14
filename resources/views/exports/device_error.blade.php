
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th style="border: 1px solid black">Device Name</th>
        <td style="border: 1px solid black">Type</td>
        <td style="border: 1px solid black">Expire date</td>
        <td style="border: 1px solid black">Register code</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
        <tr>

            <td style="border: 1px solid black">{{$item['device_name']}}</td>
            <td style="border: 1px solid black">{{$item['type']}}</td>
            <td style="border: 1px solid black">{{$item['expire_date']}}</td>
            <td style="border: 1px solid black">{{$item['device_uid']}}</td>
            <td style="border: 1px solid black">@if(@$item['error'])
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
