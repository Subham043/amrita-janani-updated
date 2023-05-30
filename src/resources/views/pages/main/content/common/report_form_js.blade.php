<script type="text/javascript" nonce="{{ csp_nonce() }}">

document.getElementById("reportModalBtn").addEventListener("click", async function() {
    await reload_captcha('captcha_container2')
});

const validationModal2 = new JustValidate('#reportForm', {
    errorFieldCssClass: 'is-invalid',
});

validationModal2
.addField('#reportMessage', [
{
    rule: 'required',
    errorMessage: 'Message is required',
},
{
    rule: 'customRegexp',
    value: /^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i,
    errorMessage: 'Message is containing invalid characters',
},
])
.addField('#captcha2', [
{
    rule: 'required',
    errorMessage: 'Captcha is required',
}
])
.onSuccess(async (event) => {
    event.target.preventDefault;
    const errorToast = (message) =>{
        iziToast.error({
            title: 'Error',
            message: message,
            position: 'bottomCenter',
            timeout:0
        });
    }
    const successToast = (message) =>{
        iziToast.success({
            title: 'Success',
            message: message,
            position: 'bottomCenter',
            timeout:0
        });
    }
    var submitBtn = document.getElementById('SubmitBtn2')
    submitBtn.innerHTML = `
        <span class="d-flex align-items-center">
            <span class="spinner-border flex-shrink-0" role="status">
                <span class="visually-hidden"></span>
            </span>
            <span class="flex-grow-1 ms-2">
                &nbsp; Submiting...
            </span>
        </span>
        `
    submitBtn.disabled = true;
    try {
        var formData = new FormData();
        formData.append('message',document.getElementById('reportMessage').value)
        formData.append('captcha',document.getElementById('captcha2').value)
        const response = await axios.post('{{$url}}', formData)
        successToast(response.data.message)
        await reload_captcha('captcha_container2')
        event.target.reset()
        setTimeout(()=>{
            location.reload()
        }, 1000)
    } catch (error) {
        if(error?.response?.data?.errors?.message){
            errorToast(error?.response?.data?.errors?.message[0])
        }
        if(error?.response?.data?.errors?.captcha){
            errorToast(error?.response?.data?.errors?.captcha[0])
        }
        if(error?.response?.data?.error){
            errorToast(error?.response?.data?.error)
        }
        if(error?.response?.data?.error_popup){
            errorPopup(error?.response?.data?.error_popup)
        }
        await reload_captcha('captcha_container2')
        document.getElementById('captcha2').value = '';
    } finally{
        submitBtn.innerHTML =  `
            Report
            `
        submitBtn.disabled = false;
    }
})

</script>
