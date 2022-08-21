@extends('layouts.main.index')

@section('css')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
    integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('main/css/content.css') }}">
@stop

@section('content')


@include('includes.main.breadcrumb')

<div class="contact-page-wrapper">

<div class="contact-form-area section-space--ptb_90">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="contact-form-wrap ml-lg-5">
                                <h3 class="title mb-40">Change Profile Details</h3>
                                <form id="contactForm">
                                    <div class="contact-form__one row">
                                        <div class="contact-input col-lg-4">
                                            <label for="Name">Name</label>
                                            <div class="contact-inner">
                                                <input name="name" id="name" type="text" value="{{Auth::user()->name}}" placeholder="Enter you name">
                                            </div>
                                        </div>

                                        <div class="contact-input col-lg-4">
                                            <label for="Phone">Phone</label>
                                            <div class="contact-inner">
                                                <input name="phone" id="phone" type="text" value="{{Auth::user()->phone}}" disabled readonly placeholder="Your Phone Number (Optional)">
                                            </div>
                                        </div>

                                        <div class="contact-input col-lg-4">
                                            <label for="Email">Email</label>
                                            <div class="contact-inner">
                                                <input name="email" id="email" type="email" value="{{Auth::user()->email}}" disabled readonly placeholder="Your Email Address ">
                                            </div>
                                        </div>

                                        
                                        <div class="submit-input col-lg-12">
                                            <button class="submit-btn" type="submit" id="SubmitBtn">Update</button>
                                            <p class="form-messege"></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

</div>

@stop

@section('javascript')
<script src="{{ asset('main/js/plugins/just-validate.production.min.js') }}"></script>
<script src="{{ asset('main/js/plugins/axios.min.js') }}"></script>

<script type="text/javascript">

const validationModal = new JustValidate('#contactForm', {
    errorFieldCssClass: 'is-invalid',
});

validationModal
.addField('#name', [
{
    rule: 'required',
    errorMessage: 'Name is required',
},
{
    rule: 'customRegexp',
    value: /^[a-zA-Z\s]*$/,
    errorMessage: 'Name is invalid',
},
])
.onSuccess(async (event) => {
    event.target.preventDefault;
    const errorToast = (message) =>{
        iziToast.error({
            title: 'Error',
            message: message,
            position: 'bottomCenter',
            timeout:7000
        });
    }
    const successToast = (message) =>{
        iziToast.success({
            title: 'Success',
            message: message,
            position: 'bottomCenter',
            timeout:6000
        });
    }
    var submitBtn = document.getElementById('SubmitBtn')
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
        formData.append('name',document.getElementById('name').value)
        const response = await axios.post('{{route('update_userprofile')}}', formData)
        successToast(response.data.message)
    } catch (error) {
        if(error?.response?.data?.form_error?.name){
            errorToast(error?.response?.data?.form_error?.name[0])
        }
        if(error?.response?.data?.error){
            errorToast(error?.response?.data?.error)
        }
    } finally{
        submitBtn.innerHTML =  `
            Update
            `
        submitBtn.disabled = false;
    }
})

</script>


@stop