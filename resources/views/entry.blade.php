
@foreach ($entries as $entry)
<div class="post-item">
<h2><a href="{{ $entry->link}}" onclick="readEntry(this,'{{ $entry->id}}')" target="_blank">{{ $entry->title}}</a></h2>
<p>{{ date("m-d H:i",$entry->published) }}  来自:<a>{{ $entry->rss->title}}</a></p>
</div>
@endforeach