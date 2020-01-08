<div class="container" style="max-width:768px;margin:100px auto 0 auto;text-align:center;">
    @include('flash')
    <div class="header-strip"></div>  
    <div class="content">
        <p>
            <span><strong>{{$meeting->title}}</strong></span><br/>
            {{date('jS F Y', strtotime($meeting->starts_on))}} to {{date('jS F Y', strtotime($meeting->ends_on))}}
        </p>
        <div>
            Enter your ID number to register for this event.
            <p>
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <input id="id_no" name="id_no" required class="form-control form-control-md" placeholder="ID No." type="text"><br/><br/>
                    <input type="hidden" name="url" value="{{$url}}" />
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </p>
        </div>
    </div>
</div>
  