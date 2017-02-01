<h4>Add a Comment</h4>
<form  method="post" action="{{{ route('comment.add',$news[0]->slug) }}}">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
    <input type="hidden" name="news_id" value="{{{ $news[0]->id }}}" />
    <input type="hidden" name="slug" value="{{{ $news[0]->slug }}}" />

    <textarea class="col-md-12 input-block-level" rows="4" name="comment" id="comment">{{{ Request::old('comment') }}}</textarea>

    <div class="form-group">
        <div class="col-md-12">
            <input type="submit" class="btn btn-default" id="submit" value="Submit" />
        </div>
    </div>
</form>
