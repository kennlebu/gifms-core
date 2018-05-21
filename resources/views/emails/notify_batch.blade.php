<html>

<head>
</head>

<body style="font-family: monospace;">
    <main>
        <div>
            Attn. {{$addressed_to->f_name}},
            <br/>
            <br/> The below detailed payments have been confirmed and processed as batch {{$batch->ref}}.
            <br/>

        </div>

        <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;" cellspacing="0">
            <tbody>
                <tr style="border-bottom:1px solid #c0c0c0;">
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="3" height="30">
                        <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">Payment Batch</span>
                        </strong>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#092d50" height="20">
                        <strong>
              <span style="color: #ffffff;">REF:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$batch->ref}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px solid #ccbcbc;" colspan="3" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #092d50;font-size:15px;">Payments:</span>
            </strong>
                    </td>
                </tr>
                @foreach($payments as $payment)
                <tr>
                    <!-- <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;color: #333333;" colspan="3" bgcolor="#ffffff">
                        <strong>Supplier: </strong> {{$payment->paid_to_name}}<br/>
                        <strong>Title: </strong> {{$payment->payment_desc}}
                    </td> -->
                    <td style="text-align: left;height: 20px;" colspan="1">
                        <strong>
              <span style="color: #333333;">Supplier:</span>
            </strong>
                    </td>
                    <td style="text-align: left;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$payment->paid_to_name}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;" colspan="1">
                        <strong>
              <span style="color: #333333;height: 20px;">Title:</span>
            </strong>
                    </td>
                    <td style="text-align: left;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$payment->payment_desc}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1">
                        <strong>
              <span style="color: #333333;height: 20px;">Amount:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{number_format($payment->amount, 2)}}</span>
            </strong>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <div>
            Login <a href="{{$js_url}}">here</a> to proceed

        </div>
    </main>
</body>

</html>