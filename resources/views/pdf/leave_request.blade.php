<html>

<head>
    <style>
        @page {
            margin: 150px 40px 120px 40px;
        }
        
        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 120px;
        }
        
        footer {
            position: fixed;
            bottom: -110px;
            left: 0px;
            right: 0px;
            background-color: #E4E8F3;
            height: 100px;
        }
        
        p {
            page-break-after: always;
        }
        
        p:last-child {
            page-break-after: never;
        }
        
        footer:after {
            content: "[Page : " counter(page) "]";
        }
    </style>
</head>

<body style="font-family: arial;">
    <header>
        <div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div>
    </header>
    <footer>
        <div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div>
    </footer>
    <main>
        <div>
            <span style="text-align:center;"><h3>LEAVE REQUEST FORM</h3></span>

            <table>
                <tr>
                    <td>Name: </td>
                    <td>{{$leave_request->requested_by->name}}</td>
                </tr>
                <tr>
                    <td>Position: </td>
                    <td>{{$leave_request->requested_by->official_post}}</td>
                </tr>
                {{-- <tr>
                    <td>Program: </td>
                    <td>HIV and Nutrition</td>
                </tr> --}}
                <tr>
                    <td>Email Address (personal): </td>
                    <td>{{$leave_request->alternate_email_1}}
                    @if(!empty($leave_request->alternate_email_2))
                    /{{$leave_request->alternate_email_2}}@endif
                    </td>
                </tr>
                <tr>
                    <td>Phone: </td>
                    <td>{{$leave_request->alternate_phone_1}}
                    @if(!empty($leave_request->alternate_phone_2))
                    /{{$leave_request->alternate_phone_2}}@endif
                    </td>
                </tr>
            </table>
            <h4>Leave details</h4>
            <table>
                <tr>
                    <td>Type of leave: </td>
                    <td>{{$leave_request->leave_type->name}}</td>
                </tr>
                <tr>
                    <td>No of days entitled to: </td>
                    <td>{{$leave_request->leave_type->days_entitled}}</td>
                </tr>
                <tr>
                    <td>No of days taken so far: </td>
                    <td>{{$leave_request->leave_type->days_taken}}</td>
                </tr>
                <tr>
                    <td>No of days requested: </td>
                    <td>{{$leave_request->no_of_days}}</td>
                </tr>
                <tr>
                    <td>Leave date: </td>
                    <td>From {{date('d F, Y', strtotime($leave_request->start_date)) }} <br/>
                        To {{date('d F, Y', strtotime($leave_request->end_date)) }}</td>
                </tr>
                <tr>
                    <td>Balance: </td>
                    <td>{{$leave_request->leave_type->days_left}}</td>
                </tr>
                <tr>
                    <td>Comments/Remarks: </td>
                    <td>{{$leave_request->requester_comments}}</td>
                </tr>
            </table>
            <br/>
            <br/>
            <div style="display:inline-block;margin-top:25px;">
                <div style="display:inline-block;margin-right:80px;">
                    Applicant:<br/>
                    <img style="height:70px;width:200px;" alt="." src="img/signatures/signature{{$leave_request->line_manager->id}}.png" />
                    <br/> {{trim($leave_request->requested_by->f_name).' '.trim($leave_request->requested_by->l_name)}}
                    <br/>{{date('d F, Y', strtotime($leave_request->created_at)) }}
                </div>
                @foreach ($unique_approvals as $key => $approval)
                <div style="display:inline-block;margin-left:80px;">
                    Line Manager:<br/>
                    <img style="height:70px;width:200px;" alt="." src="img/signatures/signature{{$leave_request->line_manager->id}}.png" />
                    <br/> {{trim($leave_request->line_manager->f_name).' '.trim($leave_request->line_manager->l_name)}}
                    <br/>{{date('d F, Y', strtotime($$approval->created_at)) }}
                </div>
                @endforeach
            </div>




        </div>
    </main>
</body>

</html>