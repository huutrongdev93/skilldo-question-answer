<section class="question-section">
    @foreach ($objects as $key => $object)
    <div class="question-wrap">
        <a data-bs-toggle="collapse" href="#collapse_{{$object->id}}" role="button" aria-expanded="false" aria-controls="collapse_{{$object->id}}">{!! ($key+1).'. '.$object->title !!}</a>
        <div class="collapse" id="collapse_{{$object->id}}">{!! $object->content !!}</div>
    </div><!-- end of Catagory -->
    @endforeach
</section><!-- End of Questions -->