<script type="text/javascript" nonce="{{ csp_nonce() }}">
    document.querySelectorAll('.btn-captcha').forEach(el => {
        el.addEventListener('click', function(){
            reload_captcha(event.target.getAttribute('data-id'))
        })
    });
    async function reload_captcha(id){
        try {
            const response = await axios.get('{{route('captcha_ajax')}}')
            document.getElementById(id).innerHTML = response.data.captcha
        } catch (error) {
            // console.log(error);
            if(error?.response?.data?.error_popup){
                errorPopup(error?.response?.data?.message)
            }
        } finally{}
    }
</script>
