/* =========================================================
   VIELLA'S CAFÉ - MENU DATABASE & CART MANAGEMENT
   ========================================================= */

const DEFAULT_PRODUCTS = [
  {
    id: "iced-coffee-toffee",
    name: "Iced Coffee Toffee",
    category: "Coffee",
    image: "iced-coffee-toffee",
    description: "A refreshing iced coffee with rich toffee flavor, topped with a swirl of whipped cream.",
    prices: { S: 25.00, M: 30.00, L: 35.00 },
    seasonal: false
  },
  {
    id: "strawberry-swirl",
    name: "Strawberry Swirl",
    category: "Tea",
    image: "strawberry-swirl",
    description: "A delightful blend of fresh strawberries and creamy milk for a sweet treat.",
    prices: { S: 24.00, M: 28.00, L: 32.00 },
    seasonal: false
  },
  {
    id: "midnight-mocha",
    name: "Midnight Mocha",
    category: "Coffee",
    image: "midnight-mocha",
    description: "A rich and indulgent blend of dark espresso, chocolate, and silky steamed milk.",
    prices: { S: 26.00, M: 32.00, L: 36.00 },
    seasonal: false
  },
  {
    id: "caramel-iced-coffee",
    name: "Caramel Iced Coffee",
    category: "Coffee",
    image: "caramel-iced-coffee",
    description: "Sweet and creamy iced coffee infused with rich caramel and toffee notes.",
    prices: { S: 25.00, M: 30.00, L: 35.00 },
    seasonal: false
  },
  {
    id: "coconut-iced-latte",
    name: "Coconut Iced Latte",
    category: "Coffee",
    image: "coconut-iced-latte",
    description: "A tropical twist on the classic iced latte made with coconut milk and vanilla.",
    prices: { S: 27.00, M: 32.00, L: 37.00 },
    seasonal: false
  },
  {
    id: "peppermint-hot-chocolate",
    name: "Peppermint Hot Chocolate",
    category: "Tea",
    image: "peppermint-hot-chocolate",
    description: "A cozy seasonal drink featuring smooth chocolate with a fresh hint of peppermint.",
    prices: { S: 22.00, M: 27.00, L: 32.00 },
    seasonal: true
  },
  {
    id: "blended-mocha-latte",
    name: "Blended Mocha Latte",
    category: "Coffee",
    image: "blended-mocha-latte",
    description: "Smooth, chocolatey blended coffee topped with generous whipped cream.",
    prices: { S: 28.00, M: 34.00, L: 38.00 },
    seasonal: false
  }
];

// Always initialize product storage if missing
if (!localStorage.getItem("viella_products")) {
  localStorage.setItem("viella_products", JSON.stringify(DEFAULT_PRODUCTS));
}

// Global Database Accessor
const DB = {
  get: function(key) {
    if (key === "products") {
      const stored = localStorage.getItem("viella_products");
      return stored ? JSON.parse(stored) : DEFAULT_PRODUCTS;
    }
    const data = localStorage.getItem("viella_" + key);
    return data ? JSON.parse(data) : [];
  },

  set: function(key, value) {
    localStorage.setItem("viella_" + key, JSON.stringify(value));
  }
};

// Global Price Formatter
function fmt(price) {
  return "TT$" + Number(price).toFixed(2);
}

// Global Cart API
const Cart = {
  getCart: function() {
    return DB.get("cart");
  },

  getItems: function() {
    return this.getCart();
  },

  add: function(productId, size = "M", quantity = 1) {
    const products = DB.get("products");
    const product = products.find(p => String(p.id) === String(productId));
    if (!product) return;

    let cart = DB.get("cart");
    const existingIndex = cart.findIndex(item => String(item.id) === String(productId) && item.size === size);

    if (existingIndex > -1) {
      cart[existingIndex].quantity += quantity;
    } else {
      cart.push({
        id: product.id,
        name: product.name,
        image: product.image,
        size: size,
        price: (product.prices && product.prices[size]) ? product.prices[size] : 25.00,
        quantity: quantity
      });
    }

    DB.set("cart", cart);
  },

  updateQty: function(productId, size, change) {
    let cart = DB.get("cart");
    const index = cart.findIndex(item => String(item.id) === String(productId) && item.size === size);

    if (index > -1) {
      cart[index].quantity += change;
      if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
      }
      DB.set("cart", cart);
    }
  },

  clear: function() {
    DB.set("cart", []);
  },

  getTotal: function() {
    const cart = this.getCart();
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  }
};