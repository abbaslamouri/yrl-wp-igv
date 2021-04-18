import selectFile from "./select-file";
const mediaUploader = function () {
  let mediaUploader;
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
    var attachment = mediaUploader.state().get("selection").first().toJSON();
    let fileUpload = document.getElementById("iwpgv__chartParams[fileUpload]");
    if (fileUpload) {
      fileUpload.value = attachment.filename;
      selectFile();
    }
  });
  mediaUploader.open();
};

export default mediaUploader;
