import FetchFunc from './fetch_func.js';

/**
 * Load and represent codes exclusive to the client and codes shared between the
 * client and the server.
 *
 * @author Darcy Driscoll <darcy.driscoll@outlook.com>
 */
export default class ErrorCodes {
  constructor(clientErrors = []) {
    this.clientCodes = clientErrors;
    this.codesURL = 'configs/error_codes.json';
  }

  /**
   * Initialise the class instance.
   *
   * @return Promise So the caller knows when initialisation is complete.
   */
  init() {
    return this.getCodes().then(codes => this.codes = codes);
  }

  /**
   * Gets the error codes and stores them in a map.
   *
   * @return Promise<Map> A Promise that tries to resolve into a Map of
   *                      error codes.
   */
  getCodes() {
    // fetch error codes json file
    return FetchFunc.fetchJSON(this.codesURL)
    .then(jsonObj => {
      let serverCodes = jsonObj.error_codes;
      let codes = serverCodes.concat(this.clientCodes);
      // store codes in map
      let codesMap = new Map();
      for (const [ix, code] of codes.entries()) {
        codesMap.set(code, ix);
      }
      return codesMap;
    })
  }

  /**
   * See if the provided code exists. Provide the Number representation of
   * the code if it does, else, throw an exception.
   *
   * TODO: better error handling
   *
   * @return Number if code is a valid error code.
   * @throws Error if code isn't a valid error code.
   */
  get(code) {
    if (this.codes.has(code)) {
      return this.codes.get(code);
    } else {
      throw new Error(`${code} is not a valid error code.`);
    }
  }

  /**
   * Return a string representation of this.codes.
   *
   * @return String
   */
  get_all() {
    let str = "";
    this.codes.forEach((value, key) => {
      let v = value;
      let k = key;
      str += `[${k}, ${v}],\n`
    });
    return str;
  }
}
