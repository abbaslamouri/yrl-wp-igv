const fetchData = async function (formData) {
  // Submit request

  const response = await fetch(yrl_wp_igv_obj.ajax_url, {
    method: "POST",
    body: formData
  });

  // Convert response to json
  const jsonRes = await response.json();
  return jsonRes;
};

export default fetchData;
