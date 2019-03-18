<html>

<head>
</head>

<body style="font-family: monospace;">
    <main>
        <div>
            Attn. {{$activity->program->program_name}} staff,
            <br/>
            <br/> The below detailed Activity has been created for this program.
            <br/>

        </div>

        <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;" cellspacing="0">
            <tbody>
                <tr style="border-bottom:1px solid #c0c0c0;">
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="3" height="30">
                        <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">ACTIVITY</span>
                        </strong>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#092d50" height="20">
                        <strong>
              <span style="color: #ffffff;">Title:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$activity->title}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Description:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$activity->description}}</span>
            </strong>
                    </td>
                </tr>
                <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                    <strong>
              <span style="color: #7c7c7c;">Program:</span>
            </strong>
                </td>
                <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                    <strong>
              <span style="color: #092d50;">{{$activity->program->program_name}}</span>
            </strong>
                </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Program Manager:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$activity->program_manager->name}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Period:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">From {{$activity->start_date}} to {{$activity->end_date}}</span>
            </strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <div>
            Login <a href="{{$js_url}}">here</a> to proceed

        </div>
    </main>
</body>

</html>