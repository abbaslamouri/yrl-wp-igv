const fetchData = async function ( url, method, wpRestNonce, body = null ) {

  let response

  if ( body ) {

    response = await fetch( url, {
      method: method,
      body: body,
      headers: {'X-WP-Nonce': wpRestNonce }
    } )

  } else {

  response = await fetch( url, {
    method: method,
    headers: {'X-WP-Nonce': wpRestNonce }
  } )

}

  return response

}

export default fetchData
