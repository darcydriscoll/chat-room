/**
 * Attributions state and logic.
 *
 * @author Darcy Driscoll <darcy.driscoll@outlook.com>
 */

import { reactive } from 'vue';

/**
 * Create an attribution object.
 *
 * @param String name Name of the thing we're deriving from.
 * @param String author Author of the thing we're deriving from.
 * @param String source Source of the thing we're deriving from.
 *
 * @return Object The params in an object.
 */
export const createAttribution = function (name, author, source) {
  return { name, author, source };
};

/**
 * Format a given set of attribution objects into a nice string.
 *
 * @param restParam attrs Attribution objects.
 *
 * @return String The attribution objects represented in a nice string.
 */
export const formatAttributions = function (attrs) {
  if (attrs === null) return '';
  let str = '';
  for (const attr of attrs) {
    str += `${attr.name} by ${attr.author} from ${attr.source}, `;
  }
  return str.slice(0, str.length - 2) + '.';
};

export const store = reactive({
  attributions: null,

  /**
   * Update attributions.
   *
   * @param restParam args The attribution objects (or null) with which to
   *  update attributions.
   */
  updateAttributions (...args) {
    if (args[0] === null) this.attributions = null;
    else this.attributions = args;
  },
});
