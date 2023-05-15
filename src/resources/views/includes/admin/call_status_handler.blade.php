<script nonce="{{ csp_nonce() }}">
    document.querySelectorAll('.status-handler').forEach(el => {
        el.addEventListener('change', function(){
            callStatusHandler(event)
        })
    });
    function callStatusHandler(event){
        event.target.parentNode.submit();
        return false;
    }
</script>
