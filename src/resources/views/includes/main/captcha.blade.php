<script type="text/javascript" nonce="{{ csp_nonce() }}">
    document.getElementById('btn-captcha').addEventListener("click", reload_captcha);
    async function reload_captcha(){
        try {
            const response = await axios.get('{{route('captcha_ajax')}}')
            document.getElementById('captcha_container').innerHTML = response.data.captcha
        } catch (error) {
            if(error?.response?.data?.error){
                errorToast(error?.response?.data?.error)
            }
            if(error?.response?.data?.message){
                errorPopup(error?.response?.data?.message)
            }
        } finally{}
    }
</script>
