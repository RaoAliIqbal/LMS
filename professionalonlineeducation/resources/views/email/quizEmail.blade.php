{{--<h4>Student Info:</h4>--}}
{{--<span><b>Name: </b> {{ $request['user']->name }}</span> <br/>--}}
{{--<span><b>Email: </b> {{ $request['user']->email }}</span> <br/>--}}
{{--<span><b>Age: </b> {{ $request['user']->age }}</span> <br/>--}}
{{--<span><b>State: </b> {{ $request['user']->state }}</span> <br/>--}}
{{--<hr>--}}

<table>
    @foreach($data['request'] as $key => $val)

        @if($val['type'] == 'single')
            <tr>

                <td>{!!  array_keys($val)[1] !!}</td>
                <td>{!! array_values($val)[1]['answer'] !!}</td>
            </tr>
        @endif

        @if($val['type'] == 'multi')
                <td>{!!  array_keys($val)[1] !!}</td>
                <td>
                    <ul style="border-bottom: 1px solid #e9e9e9;margin-bottom: 14px;">
                        @foreach(array_values($val)[1] as $multi)
                            <li>{!! $multi[0] !!}</li>
                        @endforeach
                    </ul>
                </td>
        @endif

    @endforeach

</table>
