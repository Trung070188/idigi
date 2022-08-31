
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th>User Name</th>
        <td>Full Name</td>
        <td>Password</td>
        <td>Phone</td>
        <td>Email</td>
        <td>Class</td>
        <td>Error</td>

    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
    <tr>

        <td>{{$item['username']}}</td>
        <td>{{$item['full_name']}}</td>
        <td>{{$item['password']}}</td>
        <td>{{$item['phone']}}</td>
        <td>{{$item['email']}}</td>
        <td>{{$item['class']}}</td>
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
