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





<div class="container mb-5 pb-3" style="max-width:768px;margin:100px auto 0 auto;border:1px solid lightgray;border-radius:2px;">
    @include('flash')
    <div class="header-strip"></div>  
    <div class="content">
        <div>
            <h2 style="text-align:center;">
                Fill in all details to complete your registration.
            </h2>
            <div class="col-md-12 pt-5">
                <form method="POST" action="{{ route('register-attendee') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-2">
                            ID no.:
                        </div>
                        <div class="col-10">
                            <input id="id_no" name="id_no" value="{{$id_no}}" required class="form-control form-control-md" placeholder="ID No." type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Full name:
                        </div>
                        <div class="col-10">
                            <input id="name" name="name" required class="form-control form-control-md" placeholder="Name" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Email:
                        </div>
                        <div class="col-10">
                            <input id="email" name="email" required class="form-control form-control-md" placeholder="Email" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Physical Address:
                        </div>
                        <div class="col-10">
                            <input id="physical_address" name="physical_address" required class="form-control form-control-md" placeholder="Physical Address" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Organisation:
                        </div>
                        <div class="col-10">
                            <input id="organisation" name="organisation" required class="form-control form-control-md" placeholder="Organistion." type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Designation:
                        </div>
                        <div class="col-10">
                            <input id="designation" name="designation" required class="form-control form-control-md" placeholder="Designation" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Phone:
                        </div>
                        <div class="col-10">
                            <input id="phone" name="phone" required class="form-control form-control-md" placeholder="Phone" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Bank:
                        </div>
                        <div class="col-10">
                            <input id="bank_name" name="bank_name" required class="form-control form-control-md" placeholder="Bank" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Bank branch:
                        </div>
                        <div class="col-10">
                            <input id="bank_branch_name" name="bank_branch_name" required class="form-control form-control-md" placeholder="Bank branch" type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Bank account no.:
                        </div>
                        <div class="col-10">
                            <input id="bank_account" name="bank_account" required class="form-control form-control-md" placeholder="Account no." type="text"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            KRA PIN:
                        </div>
                        <div class="col-10">
                            <input id="kra_pin" name="kra_pin" required class="form-control form-control-md" placeholder="KRA PIN" type="text"><br/>
                        </div>
                    </div>
                    <input type="hidden" name="url" value="{{$meeting_url}}" />
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <button type="submit" class="btn btn-primary">Register</button><br/>
                        </div>
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>


    </body>
</html>
  