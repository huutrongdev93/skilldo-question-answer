@foreach ($categories as $category) {
<section class="question-section">
    <div class="heading"><h3>{!! $category->name !!}</h3></div>
    @foreach ($category->objects as $key => $object)
    <div class="question-wrap">
        <a data-bs-toggle="collapse" href="#collapse_{{$object->id}}" role="button" aria-expanded="false" aria-controls="collapse_{{$object->id}}">
            {!! ($key+1).'. '.$object->title !!}
        </a>
        <div class="collapse" id="collapse_{{$object->id}}">
            {!! $object->content !!}
        </div>
    </div><!-- end of Catagory -->
    @endforeach
</section><!-- End of Questions -->
@endforeach