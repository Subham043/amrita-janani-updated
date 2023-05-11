<script nonce="{{ csp_nonce() }}">
    function callSearchHandler(){
        var str= "";
        var arr = [];
        if(document.getElementById('search').value){
            arr.push("search="+document.getElementById('search').value)
        }
        if(document.getElementById('filter').value){
            arr.push("filter="+document.getElementById('filter').value)
        }
        str = arr.join('&');
        window.location.replace('{{$url}}?'+str)
        return false;
    }
</script>
