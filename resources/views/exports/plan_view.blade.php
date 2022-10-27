@if($data)


<h1>Bảng chi tiết kế hoạch cài đặt</h1>
<span>Plan name: {{$data['plan_name']}} </span>
<br>
<span>Assign to: {{$data['assign_to']}}</span>
<br>
<span>Due date: {{$data['due_at']}}</span>
<br>
<span>Expire date: {{$data['expire_date']}}</span>
<br>
<h2>Danh sách bài giảng cần cài đặt</h2>
<br>
<h4>Tổng danh sách: {{count($data['lessons'])}}</h4>
<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th style="border: 1px solid black">No</th>
        <th style="border: 1px solid black">Lesson</th>
        <th style="border: 1px solid black">Unit name</th>
        <td style="border: 1px solid black">Subject</td>
        <td style="border: 1px solid black">Grade</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data['lessons'] as $key => $abc)

        <tr>
            <td style="border: 1px solid black">{{$key+1}}</td>
            <td style="border: 1px solid black">{{$abc['name']}}</td>
            <td style="border: 1px solid black">{{$abc['unit_name']}}</td>
            <td style="border: 1px solid black">{{$abc['subject']}}</td>
            <td style="border: 1px solid black">{{$abc['grade']}}</td>
        </tr>
    @endforeach


    </tbody>
</table>
@endif

