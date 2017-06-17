/**
 * Find an item in the collection.
 *
 * @param {Array} items
 * @param {Object|Number} item
 */
export function findItem (items, item) {
  const id = typeof item === 'object' ? item.id : item

  return items.find(i => i.id === id)
}

/**
 * Remove an item from the collection.
 *
 * @param {Array} items
 * @param {Object|Number} item
 */
export function removeItem (items, item) {
  const id = typeof item === 'object' ? item.id : item

  const index = items.findIndex(i => i.id === id)

  if (index > -1) {
    items.splice(index, 1)
  }
}

/**
 * Update an item's attributes in the collection.
 *
 * @param {Array} items
 * @param {Object} item
 */
export function updateItem (items, item) {
  const old = items.find(i => i.id === item.id)

  if (old) {
    Object.keys(item).forEach(key => {
      old[key] = item[key]
    })
  }
}
