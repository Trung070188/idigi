<h3>File school import error</h3>
<br>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th style="border: 1px solid black">School name</th>
        <th style="border: 1px solid black">Phone number</th>
        <th style="border: 1px solid black">School email</th>
        <th style="border: 1px solid black">No. of Device per User</th>
        <th style="border: 1px solid black">No. of User</th>
        <th style="border: 1px solid black">City/Provide</th>
        <th style="border: 1px solid black">District/Town</th>
        <th style="border: 1px solid black">Expire date/License</th>
        <td style="border: 1px solid black">Error</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
        <tr>

            <td style="border: 1px solid black">{{$item['label']}}</td>
            <td style="border: 1px solid black">{{$item['school_phone']}}</td>
            <td style="border: 1px solid black">{{$item['school_email']}}</td>
            <td style="border: 1px solid black">{{$item['devices_per_user']}}</td>
            <td style="border: 1px solid black">{{$item['number_of_users']}}</td>
            <td style="border: 1px solid black">{{$item['province']}}</td>
            <td style="border: 1px solid black">{{$item['district']}}</td>
            <td style="border: 1px solid black">{{$item['license_to']}}</td>
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
