'use strict';

$.fn.upload = function (param) {
    $("<input>", {type: "file", multiple: true}).change(function () {
        let files = this.files;
        for (let i = 0; i < files.length; i++) {
            let file = files[i];

            if (param.hasOwnProperty("maxsize") && file.size > param.maxsize) {
                let fileSize = (file.size / 1024 / 1024).toFixed(2),
                    uploadLimitSize = (param.maxsize / 1024 / 1024).toFixed(2);

                alert(`文件"${file.name}"的大小为${fileSize}MB，超出上传限制${uploadLimitSize}MB`);
                continue;
            }
            let formData = new FormData();

            formData.append("upload", file);
            $.ajax({
                url: param.url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: param.success,
                error: param.error
            });
        }
    }).click();
};

function getFileIcon(fileName) {
    const maps = {
        "file-archive-o": ["rar", "zip", "tar", "gz", "bz2"],
        "file-word-o": ["doc", "docx"],
        "file-excel-o": ["xls", "xlsx"],
        "file-powerpoint-o": ["ppt", "pptx"],
        "file-pdf-o": ["pdf"],
        "file-audio-o": ["mp3", "wav", "ogg", "ape", "flac"],
        "file-video-o": ["avi", "rmvb", "rm", "wmv", "flv", "swf"],
        "file-image-o": ["jpg", "jpeg", "png", "gif", "ico", "bmp"],
        "file-text-o": ["txt"],
        "file-code-o": ["c", "cpp", "h", "hpp", "java", "py", "html", "css", "js", "php", "sql"]
    };

    let ext = fileName.substr(fileName.lastIndexOf(".") + 1).toLowerCase();

    for (let type in maps) {
        if (maps[type].lastIndexOf(ext) !== -1) {
            return type;
        }
    }
    return ext[ext.length - 1] === '/' ? 'folder-open-o' : "file-o";
}

$.parseFile = function (file, editable = false) {
    let fileName = file["fileName"],
        iconClass = "fa fa-" + getFileIcon(fileName),
        random = file["random"] || '',
        url = file["url"],
        preview_url = file["preview_url"];

    let html = $("<h5>").append(
        $("<span>").addClass(iconClass)
    ).append(
        $("<a>").addClass("text-info m-1")
            .attr("href", url).attr("title", fileName)
            .text(fileName)
    ).append(
        $("<a>").addClass("text-primary ml-4")
            .attr("href", preview_url)
            .attr("target", "_blank")
            .text("预览")
    );
    if (editable) {
        html.append(
            $("<input>").css("display", "none").attr("name", "attachment[]").val(random)
        ).append(
            $("<span>").addClass("fa fa-times text-danger d-inline-block m-1")
                .css("cursor", "pointer")
                .attr("title", "删除")
                .click(function () {
                    $(this).parent().remove();
                })
        );
    }
    return html;
};