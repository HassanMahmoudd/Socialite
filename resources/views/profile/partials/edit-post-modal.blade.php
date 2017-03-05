<div class="modal fade" tabindex="-1" role="dialog" id="edit-post-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Post</h4>
            </div>
            <div class="modal-body">
                <form class="edit-post-form" enctype="multipart/form-data" name="edit-post-form" id="edit-post-form">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="control-label" for="post-body">Edit the Post</label>
                        <textarea class="form-control" name="body" id="post-body" rows="5"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label" for="image">Change the image</label>
                                <input id="edit-post-image" type="file" class="form-control" name="image">

                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-1">
                            <h5>Public</h5>
                            <div class="toggle">

                                <input id="is-public-modal" type="checkbox" name="isPublic" checked/>
                                <span class="btn"></span>
                                <span class="bg"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-post-modal">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->