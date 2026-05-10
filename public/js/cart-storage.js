/**
 * TradeCoin — cart di localStorage
 * Key: trade_coin_cart
 * Item: { id, nama_produk, nama_penjual, harga, gambar, quantity, selected }
 */
(function (global) {
  var STORAGE_KEY = 'trade_coin_cart';

  function safeParse(json, fallback) {
    try {
      var v = JSON.parse(json);
      return Array.isArray(v) ? v : fallback;
    } catch (e) {
      return fallback;
    }
  }

  function getCart() {
    return safeParse(localStorage.getItem(STORAGE_KEY) || '[]', []);
  }

  function setCart(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    window.dispatchEvent(new CustomEvent('tradeCoinCartUpdated', { detail: { count: items.length } }));
  }

  /**
   * @param {object} product — minimal: id (number), nama_produk, harga, optional nama_penjual, gambar
   * @param {number} qty
   */
  function addOrIncrement(product, qty) {
    qty = Math.max(1, parseInt(qty, 10) || 1);
    var id = parseInt(product.id, 10);
    if (!id || id < 1) return { ok: false, error: 'ID produk tidak valid' };

    var cart = getCart();
    var idx = cart.findIndex(function (row) { return parseInt(row.id, 10) === id; });
    if (idx >= 0) {
      cart[idx].quantity = (parseInt(cart[idx].quantity, 10) || 0) + qty;
      cart[idx].nama_produk = product.nama_produk || cart[idx].nama_produk;
      cart[idx].nama_penjual = product.nama_penjual != null ? product.nama_penjual : cart[idx].nama_penjual;
      cart[idx].harga = Number(product.harga) || cart[idx].harga;
      cart[idx].gambar = product.gambar != null ? product.gambar : cart[idx].gambar;
    } else {
      cart.push({
        id: id,
        nama_produk: String(product.nama_produk || ''),
        nama_penjual: String(product.nama_penjual || ''),
        harga: Number(product.harga) || 0,
        gambar: String(product.gambar || ''),
        quantity: qty,
        selected: true
      });
    }
    setCart(cart);
    return { ok: true, cart: cart };
  }

  function updateQuantity(productId, newQty) {
    newQty = parseInt(newQty, 10);
    if (isNaN(newQty) || newQty < 1) return removeItem(productId);
    var cart = getCart();
    var idx = cart.findIndex(function (row) { return parseInt(row.id, 10) === parseInt(productId, 10); });
    if (idx < 0) return;
    cart[idx].quantity = newQty;
    setCart(cart);
  }

  function increment(productId, delta) {
    delta = parseInt(delta, 10) || 0;
    var cart = getCart();
    var idx = cart.findIndex(function (row) { return parseInt(row.id, 10) === parseInt(productId, 10); });
    if (idx < 0) return;
    var q = (parseInt(cart[idx].quantity, 10) || 0) + delta;
    if (q < 1) return removeItem(productId);
    cart[idx].quantity = q;
    setCart(cart);
  }

  function removeItem(productId) {
    var cart = getCart().filter(function (row) {
      return parseInt(row.id, 10) !== parseInt(productId, 10);
    });
    setCart(cart);
  }

  function setSelected(productId, selected) {
    var cart = getCart();
    var idx = cart.findIndex(function (row) { return parseInt(row.id, 10) === parseInt(productId, 10); });
    if (idx < 0) return;
    cart[idx].selected = !!selected;
    setCart(cart);
  }

  function selectedTotalCoins() {
    return getCart().reduce(function (sum, row) {
      if (!row.selected) return sum;
      var h = Number(row.harga) || 0;
      var q = parseInt(row.quantity, 10) || 0;
      return sum + h * q;
    }, 0);
  }

  function clearSelected() {
    var cart = getCart().filter(function (row) { return !row.selected; });
    setCart(cart);
  }

  function imageUrl(gambar) {
    var g = String(gambar || '').trim();
    if (!g) return '/foto/default.png';
    if (g.indexOf('http://') === 0 || g.indexOf('https://') === 0) return g;
    if (g.charAt(0) === '/') return g;
    return '/' + g;
  }

  global.TradeCoinCart = {
    STORAGE_KEY: STORAGE_KEY,
    getCart: getCart,
    setCart: setCart,
    addOrIncrement: addOrIncrement,
    updateQuantity: updateQuantity,
    increment: increment,
    removeItem: removeItem,
    setSelected: setSelected,
    selectedTotalCoins: selectedTotalCoins,
    clearSelected: clearSelected,
    imageUrl: imageUrl
  };
})(typeof window !== 'undefined' ? window : this);
