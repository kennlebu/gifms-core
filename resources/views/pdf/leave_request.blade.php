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
        
        .bold {
            font-weight: bold;
            color: #000000;
        }
        
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        
        td,
        th {
            border: 1px solid #CCC;
            padding: 2px;
        }
        
        footer:after {
            content: "[Page : " counter(page) "]";
        }
    </style>
</head>

<body style="font-family: arial; font-size:14px;">
    <header>
        <div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div>
    </header>
    <footer>
        <div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div>
    </footer>
    <main>
        <div>
            <div>
                <h2 style="text-align:center"><span style="font-weight:bold;color:#000000">LEAVE REQUEST FORM</span></h2>
                <p> </p>
                <p>
                    <div style="width:50%;float:left;"><span class="bold">Name:</span> {{$leave_request->requested_by->name}}</div>
                    <div><span style="font-size:11pt;font-weight:bold;color:#000000"> <wbr>Position:</span> {{$leave_request->requested_by->official_post}}<wbr></div>
                </p>

                <!-- <h1><span style="font-size:11pt;font-weight:bold;color:#000000">Program: ______________________________<wbr>________________ 
            Nairobi, Kenya</span></h1>
                    <p> </p> -->
                <p><span class="bold">Leave start date (from):</span> {{date('d F, Y', strtotime($leave_request->start_date)) }}
                    <span class="bold">and -leave end date (to):</span> {{date('d F, Y', strtotime($leave_request->end_date)) }}
                </p>
                <p><span class="bold">Your contacts you are away (phone numbers with country codes): </span><br/>
                    <span style="font-size:11pt;font-weight:bold;color:#000000">Alternative number (s):</span> {{$leave_request->alternate_phone_1}} <wbr>@if(!empty($leave_request->alternate_phone_2)) / {{$leave_request->alternate_phone_2}}@endif
                </p>
                <p><span class="bold">Email address (personal):</span> {{$leave_request->alternate_email_1}} <wbr>@if(!empty($leave_request->alternate_email_2)) / {{$leave_request->alternate_email_2}}@endif
                </p>
                <h1><span style="font-size:11pt;font-weight:bold;text-decoration:underline;color:#000000">Nature of leave:</span></h1>
                <ul style="list-style:disc">
                    <li style="margin-left:0pt">
                        <span style="font-size:10pt;color:#000000">Place X in the nature of leave </span>
                    </li>
                    <li style="margin-left:0pt">
                        <span style="font-size:10pt;color:#000000">Fill in number of days and dates in the appropriate line</span>
                    </li>
                </ul>
                <p> </p>
                </div>
                <div style="text-align:left">
                    <table class="table table-bordered">
                            <tr>
                                <th>Mark (x)</th>
                                <th>Nature of leave </th>
                                <th>No. of days entitled to</th>
                                <th>No of days taken so far</th>
                                <th>No of days now requested </th>
                                <th>Exact dates (from date to date) </th>
                                <th>Balance </th>
                                <th>Remarks</th>
                            </tr>
                            @foreach($leave_request_types as $request_type)
                            <tr>
                                @if($request_type->id == $leave_request->leave_type_id)
                                <td style="text-align: center;"> X </td>
                                <td>
                                    <span class="bold">{{$request_type->name}} </span>
                                </td>
                                <td>{{$request_type->days_entitled}} </td>
                                <td>{{$leave_request->days_taken}} </td>
                                <td>{{$leave_request->no_of_days}} </td>
                                <td style="font-size:12px;">{{date('d F, Y', strtotime($leave_request->start_date)) }} to <wbr> {{date('d F, Y', strtotime($leave_request->end_date)) }} </td>
                                <td>{{$leave_request->days_left}} </td>
                                <td>{{$leave_request->requester_comments}} </td>
                                @else
                                <td> </td>
                                <td>
                                    <span class="bold">{{$request_type->name}} </span>
                                </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                @endif
                            </tr>
                            @endforeach
                    </table>
                </div>
                <p> </p>
                <p> </p>
                <!-- <h1><span style="font-size:11pt;font-weight:bold;color:#000000 ">Other (specify-weekends/public 
            holiday, etc.) ______________________________<wbr>_____</span></h1>
                <h1><span style="font-size:11pt;font-weight:bold;color:#000000 ">No of days) _______________</span></h1>
                <p> </p> -->

                <span class="bold">Note:</span>
                <ul style="list-style:disc ">
                    <li style="margin-left:0pt;font-size:10pt; ">
                        <span>Public holidays and weekends are taken as TOIL if actually staff worked 
              on this day, requires prior approval by line manager</span>
                    </li>
                    <li style="margin-left:0pt;font-size:10pt; ">
                        <span>Saturday and Sundays should not be calculated as annual leave days</span>
                    </li>
                    <li style="margin-left:0pt;font-size:10pt; ">
                        <span>For national staff, public holidays are as declared by the Government. The days are indicated on the yearly calendar circulated at the beginning 
              of the year. Any additional holidays will be declared and gazetted by the Government</span>
                    </li>
                </ul>
                <p> </p>
                <div style="display:inline-block;margin-top:25px;">
                    <div style="display:inline-block;margin-right:80px;">
                        <strong>Applicant:</strong><br/>
                        <img style="height:70px;width:200px;" alt="." src="{{asset('storage/signatures/'.$leave_request->requested_by->signature)}}" />
                        <br/> {{trim($leave_request->requested_by->f_name).' '.trim($leave_request->requested_by->l_name)}}
                        <br/>{{date('d F, Y', strtotime($leave_request->created_at)) }}
                    </div>
                    @foreach ($unique_approvals as $key => $approval)
                    <div style="display:inline-block;margin-left:80px;">
                        <strong>Line Manager:</strong><br/>
                        <img style="height:70px;width:200px;" alt="." src="img/signatures/signature{{$leave_request->line_manager_id}}.png" />
                        <br/> {{trim($leave_request->line_manager->f_name).' '.trim($leave_request->line_manager->l_name)}}
                        <br/>{{date('d F, Y', strtotime($$approval->created_at)) }}
                    </div>
                    @endforeach
                </div>
            </div>
    </main>
</body>

</html>