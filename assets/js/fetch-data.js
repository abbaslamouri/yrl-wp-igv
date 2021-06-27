const fetchData = async function (url, method, wpRestNonce, body = null) {

  const response = await fetch(url, {
    method: method,
    headers: {'X-WP-Nonce': wpRestNonce }
  })

  return response.json()

}

export default fetchData
