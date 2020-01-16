<div class="container" style="max-width:768px;margin:100px auto 0 auto;text-align:center;border:1px solid lightgray;border-radius:2px;">
    @include('flash')
    <div class="header-strip"></div>  
    <div class="content">
        <div>
            Fill in all details to complete your registration.
            <p>
                <form method="POST" action="{{ route('register-attendee') }}">
                    {{ csrf_field() }}
                    ID no.: <input id="id_no" name="id_no" value="{{$id_no}}" required class="form-control form-control-md" placeholder="ID No." type="text"><br/><br/>
                    Full name: <input id="name" name="name" required class="form-control form-control-md" placeholder="Name" type="text"><br/><br/>
                    Organisation: <input id="organisation" name="organisation" required class="form-control form-control-md" placeholder="Organistion." type="text"><br/><br/>
                    Designation: <input id="designation" name="designation" required class="form-control form-control-md" placeholder="Designation" type="text"><br/><br/>
                    Phone: <input id="phone" name="phone" required class="form-control form-control-md" placeholder="Phone" type="text"><br/><br/>
                    Bank: <input id="bank_name" name="bank_name" required class="form-control form-control-md" placeholder="Bank" type="text"><br/><br/>
                    Bank branch: <input id="bank_branch_name" name="bank_branch_name" required class="form-control form-control-md" placeholder="Bank branch" type="text"><br/><br/>
                    Bank account no.: <input id="bank_account" name="bank_account" required class="form-control form-control-md" placeholder="Account no." type="text"><br/><br/>
                    KRA PIN: <input id="kra_pin" name="kra_pin" required class="form-control form-control-md" placeholder="KRA PIN" type="text"><br/><br/>
                    <input type="hidden" name="url" value="{{$meeting_url}}" />
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </p>
        </div>
    </div>
</div>
  