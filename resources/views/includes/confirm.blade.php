@if (isset($confirm))
    <div class="container-fluid mt-4 mb-4">
        <form action="{{Request::fullUrlWithQuery(['confirm' => true])}}" method="post">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm action</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure about this action</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-check-double"></i> Confirm</button>
                        </div>
                    </div>
                </div>

        </form>
    </div>
@endif
