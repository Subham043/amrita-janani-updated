<script nonce="{{ csp_nonce() }}">
    document.getElementById('search_form_sub_menu').addEventListener('submit', callSearchHandler)
    function callSearchHandler(event){
        event.preventDefault();
        var str= "";
        var arr = [];

        if(document.getElementById('search').value){
            arr.push("search="+document.getElementById('search').value)
        }


        str = arr.join('&');
        window.location.replace('{{$search_url}}?'+str)
    }
</script>
