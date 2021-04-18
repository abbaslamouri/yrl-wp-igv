import selectFile from "./select-file";

const mediaUploader = function (iwpgvObj, prefix) {
  let mediaUploader;
  if (mediaUploader) {
    mediaUploader.open();
    return;
  }
  mediaUploader = wp.media.frames.file_frame = wp.media({
    title: "Select File",
    button: {
      text: "Select File",
    },
    multiple: false,
  });
  mediaUploader.on("select", function (event) {
    var attachment = mediaUploader.state().get("selection").first().toJSON();
    let fileUpload = document.getElementById(`${prefix}__chartParams[fileUpload]`);
    if (fileUpload) {
      fileUpload.value = attachment.filename;
      selectFile(iwpgvObj, prefix);
    }
  });
  mediaUploader.open();
};

export default mediaUploader;
