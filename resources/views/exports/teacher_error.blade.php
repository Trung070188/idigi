<h3>File teacher import error</h3>
<br>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th style="border: 1px solid black">User name</th>
        <th style="border: 1px solid black">Full name</th>
        <th style="border: 1px solid black">Password</th>
        <th style="border: 1px solid black">Phone</th>
        <th style="border: 1px solid black">Email</th>
        <th style="border: 1px solid black">Class</th>
        <th style="border: 1px solid black">Error</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
    <tr>

        <td style="border: 1px solid black">{{$item['username']}}</td>
        <td style="border: 1px solid black">{{$item['full_name']}}</td>
        <td style="border: 1px solid black">{{$item['password']}}</td>
        <td style="border: 1px solid black">{{$item['phone']}}</td>
        <td style="border: 1px solid black">{{$item['email']}}</td>
        <td style="border: 1px solid black">{{$item['class']}}</td>
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
