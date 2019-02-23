$__$.registerJQueryPlugin("fileInputIntegrate",function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
    var currentUrl = "";

	this.onInit = function(){
        $elem.on("change",function(){
            if($elem.val())
                return;
            var url = $elem.realVal();
            if((url || currentUrl) /*&& (url != currentUrl)*/){
                options.url = url;
                currentUrl = url;
                $self.initPlugin();
            }
        });
        $elem.parents("form").on("reset",function(){
            options.url = "";
            $self.initPlugin();
        });
        $self.initPlugin();
	}

	this.onUpdate = function(){
        
	}

    this.initPlugin = function(){
        if(options.fileType){
            options.allowedFileTypes = options.fileType.split("|");
        }
        if(options.url && options.file_type=="image"){
            options.initialPreview = [
                "<img src='"+options.url+"' class='file-preview-image'>",
            ];
            options.overwriteInitial = true;
        } else {
            options.initialPreview = [];
        }
        if ($elem.data('fileinput')) {
            $elem.fileinput("clear");
            $elem.fileinput('destroy');
        }
        $elem.fileinput(options);
    }

},{
	showUpload : false,
	previewFileIcon: '<i class="fa fa-file"></i>',
    allowedPreviewTypes: [
    	"image", "video", "audio"
    ], // set to empty, null or false to disable preview for all types
    previewFileIconSettings: {
        'doc': '<i class="fa fa-file-word-o text-primary"></i>',
        'xls': '<i class="fa fa-file-excel-o text-success"></i>',
        'ppt': '<i class="fa fa-file-powerpoint-o text-danger"></i>',
        'jpg': '<i class="fa fa-file-photo-o text-warning"></i>',
        'pdf': '<i class="fa fa-file-pdf-o text-danger"></i>',
        'zip': '<i class="fa fa-file-archive-o text-muted"></i>',
        'htm': '<i class="fa fa-file-code-o text-info"></i>',
        'txt': '<i class="fa fa-file-text-o text-info"></i>',
        'mov': '<i class="fa fa-file-movie-o text-warning"></i>',
        'mp3': '<i class="fa fa-file-audio-o text-warning"></i>',
    },
    previewFileExtSettings: {
        'doc': function(ext) {
            return ext.match(/(doc|docx)$/i);
        },
        'xls': function(ext) {
            return ext.match(/(xls|xlsx)$/i);
        },
        'ppt': function(ext) {
            return ext.match(/(ppt|pptx)$/i);
        },
        'zip': function(ext) {
            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
        },
        'htm': function(ext) {
            return ext.match(/(php|js|css|htm|html)$/i);
        },
        'txt': function(ext) {
            return ext.match(/(txt|ini|md)$/i);
        },
        'mov': function(ext) {
            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
        },
        'mp3': function(ext) {
            return ext.match(/(mp3|wav)$/i);
        },
    }
},"[input-file]")