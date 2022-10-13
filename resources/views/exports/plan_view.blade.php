@if($data)


<h1>Bảng chi tiết kế hoạch cài đặt</h1>
<span>Plan name : {{$data['plan_name']}} </span>
<br>
<span>Assign to : {{$data['assign_to']}}</span>
<br>
<span>Due date : {{$data['due_at']}}</span>
<br>
<span>Expire date : {{$data['expire_date']}}</span>
<br>

<h2>Danh sách bài giảng cần cài đặt</h2>
<br>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th>No</th>
        <th>Lesson</th>
        <th>Unit</th>
        <td>Subject</td>
        <td>Grade</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data['lessons'] as $key => $abc)

        <tr>
            <td>{{$key+1}}</td>
            <td>{{$abc['name']}}</td>
            <td>{{$abc['unit']}}</td>
            <td>{{$abc['subject']}}</td>
            <td>{{$abc['grade']}}</td>
        </tr>
    @endforeach


    </tbody>
</table>

@endif

