/**
 * @typedef {Object} Breadcrumb
 *
 * @property {string} title
 * @property {string|null} url
 */

/**
 * @typedef {Object} Feed
 *
 * @property {number} category_id
 * @property {string} created_at
 * @property {string|null} favicon_url
 * @property {string} feed_url
 * @property {number} id
 * @property {string} language
 * @property {string|null} last_checked_at
 * @property {string} name
 * @property {string} site_url
 * @property {string} updated_at
 * @property {number} user_id
 */

/**
 * @typedef {Object} FeedItem
 *
 * @property {string} checksum
 * @property {string} created_at
 * @property {string} description
 * @property {Feed} feed
 * @property {number} feed_id
 * @property {boolean} has_image
 * @property {number} id
 * @property {string|null} image_mimetype
 * @property {string|null} image_url
 * @property {number} laravel_through_key
 * @property {string} posted_at
 * @property {string|null} read_at
 * @property {string} title
 * @property {string} updated_at
 * @property {string} url
 */

export default {};
