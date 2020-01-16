<div class="container" style="max-width:768px;margin:100px auto 0 auto;text-align:center;border:1px solid lightgray;border-radius:2px;">
    @include('flash')
    <div class="header-strip"></div>  
    <div class="content">
        <p>
            <span><strong>{{$meeting->title}}</strong></span><br/>
            {{date('jS F Y', strtotime($meeting->starts_on))}} to {{date('jS F Y', strtotime($meeting->ends_on))}}</p>
        <div>
            Hi {{$attendee->name}}, we have received your registration. See you at the event!
        </div>
    </div>
</div>
  