<link href="{{ asset('admin/css/tagify.css' ) }}" rel="stylesheet" type="text/css" />

<script src="{{ asset('admin/js/pages/tagify.min.js') }}"></script>
<script src="{{ asset('admin/js/pages/tagify.polyfills.min.js') }}"></script>

<script type="text/javascript" nonce="{{ csp_nonce() }}">
    var tagElem = [];
    @if($tags_exist)
        @foreach($tags_exist as $tag)
        tagElem.push("{{$tag}}")
        @endforeach
    @endif
var availableTags = "";
var tagInput = document.getElementById('tags'),
tagify = new Tagify(tagInput, {
    whitelist : tagElem,
    dropdown : {
        classname     : "color-blue",
        enabled       : 0,              // show the dropdown immediately on focus
        position      : "text",         // place the dropdown near the typed text
        closeOnSelect : false,          // keep the dropdown open after selecting a suggestion
        highlightFirst: true
    }
});
@if($country->tags)
availableTags = "{{$country->tags}}"
tagify.addTags(availableTags.split(','))
@endif
</script>

<script type="text/javascript" nonce="{{ csp_nonce() }}">
    var topicElem = [];
    @if($topics_exist)
        @foreach($topics_exist as $topic)
        topicElem.push("{{$topic}}")
        @endforeach
    @endif
var availableTopics = "";
var topicInput = document.getElementById('topics'),
tagifyTopic = new Tagify(topicInput, {
    whitelist : topicElem,
    dropdown : {
        classname     : "color-blue",
        enabled       : 0,              // show the dropdown immediately on focus
        position      : "text",         // place the dropdown near the typed text
        closeOnSelect : false,          // keep the dropdown open after selecting a suggestion
        highlightFirst: true
    }
});
@if($country->topics)
availableTopics = "{{$country->topics}}"
tagifyTopic.addTags(availableTopics.split(','))
@endif
</script>
