/*$(function(){
    $(".upload_preview").each(function(){
        if($(this).hasAttr("data-image-id"))
            $(this).upload_preview_image_ref($(this).data());
        else
            $(this).upload_preview($(this).data());
    });
});*/

$__$.registerJQueryPlugin("inputUploadPreview",function(){
    var $self = this;
    $self.onInit = function(){
        $self.update();
    };

    $self.onUpdate = function(){
        //$self.update();
    };

    $self.update = function(){
         if($self.$elem.hasAttr("data-image-id"))
            $self.$elem.upload_preview_image_ref($self.options);
        else
            $self.$elem.upload_preview($self.options);
    };

},{},"upload_preview");

$.fn.upload_preview_image_ref = function(options){
    var fileReaderSupported = window.File && window.FileReader && window.FileList && window.Blob;
    if(!fileReaderSupported)
        return;
    options = $.extend({
        "clickToChangeLabel" : "click to change",
        "clickToCancelLabel" : "click to cancel"
    },options);
    var $self = $(this);
    $self.hide();
    var imageId = $self.attr("data-image-id");
    var $img = $("#"+imageId);
    var originSrc = $img.attr("src");
    $img.css("cursor","pointer");

    $self.change(function(evt){
        console.log("change");
        var files = evt.target.files;
        var f;
        if(files && files.length)
            f = files[0];
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                // Render thumbnail.
                if (!f.type.match('image.*')) {
                    // not image
                    $__$.alert("Please select an image!");
                    imageMode();
                }
                else
                {
                    // is image
                    $img.attr("src",e.target.result);
                    fileMode();
                }
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    });

    var imageMode = function(){
        if($img.attr("src")!=originSrc)
            $img.attr("src",originSrc);
        $img.attr("title",options.clickToChangeLabel);
        $img.off("click").on("click",function(){
            $self.click();
        });
    };

    var fileMode = function(){
        $img.attr("title",options.clickToCancelLabel);
        $img.off("click").on("click",function(){
            // cancel
            $clone = $self.clone(true); 
            $self.replaceWith($clone);
            imageMode();
        });
    };

    imageMode();
};

$.fn.upload_preview = function(){
    var fileReaderSupported = window.File && window.FileReader && window.FileList && window.Blob;
    if(!fileReaderSupported)
        return;
    var $self = $(this);
    var $clone = $self.clone(true);
    var $wrapper, $previewContainer, $preview, $removeButton, $selectContainer, $nameText;

    _init();

    $self.change(function(evt){
        console.log("change");
        var files = evt.target.files;
        var f;
        if(files && files.length)
            f = files[0];
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                // Render thumbnail.
                $selectContainer.hide();
                $previewContainer.show();
                if (!f.type.match('image.*')) {
                    // not image
                    $preview.html('<i class="icon-file"></i>');
                }
                else
                {
                    // is image
                    $preview.html('<img src="'+e.target.result+'" />');
                }
                $nameText.text(theFile.name);
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    });

    // private functions
    function _init()
    {
        $wrapper = $('<div class="uploadContainer"></div>');
        $self.wrap($wrapper);
        $wrapper = $self.parent();
        $self.hide();
        $select = $('<div class="select"><i class="icon-plus"></i></div>');
        $selectContainer = $('<div class="selectContainer"></div>').append($select);
        $preview = $('<div class="preview"></div>')
        $removeButton = $('<button type="button" class="removeButton close">&times;</button>');
        $nameText = $('<div class="nameText text-info" style="font-weight:bold"></div>');
        $previewContainer = $('<div class="previewContainer"></div>').append($preview).append($nameText).append($removeButton).hide();
        $wrapper.append($selectContainer).append($previewContainer);
        $removeButton.click(function(){
            $selectContainer.show();
            $previewContainer.hide();
            var $clone = $self.clone(true);
            $self.remove();
            $wrapper.append($clone);
            $self = $clone;
        });
        $select.click(function(){
            $self.click();
        });
    }
};