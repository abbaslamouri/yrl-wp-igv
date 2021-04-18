import selectFile from "./select-file";
const mediaUploader = function () {
  jQuery(document).ready(function ($) {
    var mediaUploader;
    $(document).on("click", "#iwpgv__mediaUploadBtn", function (e) {
      e.preventDefault();
      if (mediaUploader) {
        mediaUploader.open();
        return;
      }
      mediaUploader = wp.media.frames.file_frame = wp.media({
        title: "Choose Image",
        button: {
          text: "Choose Image",
        },
        multiple: false,
      });
      mediaUploader.on("select", function (event) {
        var attachment = mediaUploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        $("#iwpgv__fileUpload").val(attachment.filename);
        selectFile();
      });
      mediaUploader.open();
    });
  });
};

export default mediaUploader;
