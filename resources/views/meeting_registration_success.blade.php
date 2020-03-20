<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Event Registration</title>

        <!-- Fonts -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    </head>

    <body>


<div class="container" style="max-width:768px;margin:100px auto 0 auto;text-align:center;border:1px solid lightgray;border-radius:2px;">
    @include('flash')
    <div class="header-strip"></div>  
    <div class="content">
        <h2>
            <span><strong>{{$meeting->title}}</strong></span><br/>
        </h2>
        <h5>
            {{date('jS F Y', strtotime($meeting->starts_on))}} to {{date('jS F Y', strtotime($meeting->ends_on))}}
        </h5>
        <div class="row" style="margin-top:50px;">
            <div class="col-md-1 hidden-xs"></div>
                <div class="col-md-10">
                    Hi <strong>{{$attendee->name}}</strong>, we have received your registration. See you at the event!
                </div>
            <div class="col-md-1 hidden-xs"></div>
        </div>
    </div>
</div>


</body>
</html>
  