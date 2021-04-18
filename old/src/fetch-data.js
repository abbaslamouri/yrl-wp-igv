const fetchData = async function (formData) {
  // Submit request
  const response = await fetch(iwpgv_obj.ajax_url, {
    method: "POST",
    body: formData,
  });

  // Convert response to json
  const jsonRes = await response.json();
  console.log(jsonRes);
  return jsonRes;
};

export default fetchData;
