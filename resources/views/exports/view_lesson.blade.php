<table class="table table-bordered" style="width: 100%">
    <thead>
    <tr>
        <th>Name</th>
        <td>Subject</td>
        <td>Grade</td>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $item)
        <tr>

            <td>{{$item['name']}}</td>
            <td>{{$item['subject']}}</td>
            <td>{{$item['grade']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
