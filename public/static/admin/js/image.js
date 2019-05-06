$(function() {
    $("#file_upload").uploadify({
        'swf'             : SCOPE.uploadify_swf,
        'uploader'        : SCOPE.image_upload,
        'buttonText'      : '图片上传',
        'onUploadSuccess' : function(file, data, response) {

        }
    });
});