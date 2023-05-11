<link href="{{ asset('admin/libs/quill/quill.snow.css' ) }}" rel="stylesheet" type="text/css" />

<style nonce="{{ csp_nonce() }}">
    #description{
        min-height: 200px;
    }
</style>

<script src="{{ asset('admin/libs/quill/quill.min.js' ) }}"></script>

<script type="text/javascript" nonce="{{ csp_nonce() }}">
    var quillDescription = new Quill('#description', {
        theme: 'snow'
    });
</script>
