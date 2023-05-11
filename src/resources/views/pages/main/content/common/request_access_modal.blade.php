<!-- Request Access Modal -->
<div class="modal fade" id="requestAccessModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request Access</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="requestAccessForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reasonForAccess">Reason For Access</label>
                            <textarea class="form-control" id="reasonForAccess" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="reasonForAccess">Captcha</label>
                            <div class="d-flex align-items-center gap-2">
                                <p id="captcha_container1">{!!captcha_img()!!} </p>
                                <span class="btn-captcha" data-id="captcha_container1" title="reload captcha"><i class="fas fa-redo" data-id="captcha_container1"></i></span>
                            </div>
                            <input type="text" class="form-control" id="captcha1" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="SubmitBtn">Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
