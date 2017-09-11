<table width="400" border="1">
    <tr>
        <td width="50%">姓名</td>
        <td width="20%">
            上周({{$week1->format("W")}})<br>
            {{count($weekly1s)}}
        </td>
        <td width="20%">
            本周({{$week2->format("W")}})<br>
            {{count($weekly2s)}}
        </td>
    </tr>
    @foreach ($staffs as $staff)
    <tr>
        <td>{{$staff->name}}</td>
        <td>
            @if( in_array($staff->id ,$weekly1s))
            已交
            @else
            <span style="color:#ff5500">未交</span>
            @endif
        </td>
        <td>
            @if( in_array($staff->id ,$weekly2s))
            已交
            @else
            <span style="color:#ff5500">未交</span>
            @endif
        </td>
    </tr>
    @endforeach
</table>