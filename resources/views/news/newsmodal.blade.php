<div class="modal fade" id="flashNews" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-sm">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h4 class="modal-title" id="myModalLabel">TrueBlue MapMiner Updates</h4>
            </div>
            <div class="modal-body">
               @foreach ($news as $new)
                <h4>{{$new->title}}</h4>
                <p>Dateline: {{date('M jS, Y'  ,strtotime($new->datefrom))}}</p>
                @if(strlen($new->news) > 100)
                <p>{!! substr($new->news,0,100) . "<a href=\"".route('news.show',$new->slug)."\">&#8250 Read More</a>"!!}</p>
                @else
                <p>{!!$new->news!!}</p>
                @endif
                <hr />
                @endforeach
            </div>
            <div class="modal-footer">
            <div class='pull-left'>
            <input type='checkbox' id='nonews' name='noNews' />
            Check if you don't want to see any more old news!</div>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>