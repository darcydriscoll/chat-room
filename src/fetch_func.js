export default class FetchFunc {
  /**
   * Requests from the given URL and reads as JSON.
   *
   * @param string url The URL we want to request from.
   * @param object init The options we want to fetch with. Allows us to e.g.
   *                    execute a POST request.
   *
   * @return Promise A promise that resolves to the JSON representation of the
   *                 URL we requested from.
   */
  static fetchJSON (url, init = {}) {
    return fetch(url, init)
      .then(response => {
        // console.log(`status: ${response.status} ${response.statusText}`);
        return response.json();
      });
  }
}
