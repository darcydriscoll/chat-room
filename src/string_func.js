export default class StringFunc {
  /**
   * Evaluates whether a given character is valid for use in a nickname.
   *
   * Valid characters are those characters found on a typical QWERTY keyboard,
   * incl. the pound sign and excl. Space.
   *
   * @param string ch The character we want to evaluate.
   *
   * @return Boolean True if the character is valid, false otherwise.
   */
  static isCharacterValid (ch) {
    // we consider falsy chars valid to avoid exceptions with codePointAt
    if (ch) {
      const chCode = ch.codePointAt(0);
      if ((ch !== '£') && (chCode < 33 || chCode > 126)) {
        return false;
      }
    }
    // passed checks -- fall into true
    return true;
  }

  /**
   * Check whether the given nickname is valid.
   *
   * A nickname is valid if it is between 3-20 characters, and each character
   * passes StringFunc.isCharacterValid.
   *
   * @param string nick The nickname we want to assess
   *
   * @return Boolean true if the nickname is valid, false otherwise.
   */
  static isNicknameValid (nick) {
    if (nick.length < 3 || nick.length > 20) {
      return false;
    }
    // check each character
    for (const code of nick) {
      if (!this.isCharacterValid(code)) {
        return false;
      }
    }
    // passed all checks -- fall into true
    return true;
  }
}
