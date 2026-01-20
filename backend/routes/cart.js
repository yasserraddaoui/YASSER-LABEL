const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const User = require('../models/User');
const Product = require('../models/Product');
const { auth } = require('../middleware/auth');

// Get user's cart
router.get('/', auth, async (req, res) => {
    try {
        const user = await User.findById(req.user._id)
            .populate('cart.product')
            .select('cart');
        
        if (!user) {
            return res.status(404).json({ message: 'User not found' });
        }

        res.json(user.cart);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Add item to cart
router.post('/add', [
    auth,
    body('productId').notEmpty().withMessage('Product ID is required'),
    body('quantity').isInt({ min: 1 }).withMessage('Quantity must be at least 1'),
    body('size').isIn(['XS', 'S', 'M', 'L', 'XL', 'XXL']).withMessage('Invalid size'),
    body('color').notEmpty().withMessage('Color is required')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { productId, quantity, size, color } = req.body;

        // Check if product exists and has enough stock
        const product = await Product.findById(productId);
        if (!product) {
            return res.status(404).json({ message: 'Product not found' });
        }

        if (product.stock < quantity) {
            return res.status(400).json({ message: 'Not enough stock available' });
        }

        // Check if product is already in cart
        const user = await User.findById(req.user._id);
        const cartItemIndex = user.cart.findIndex(
            item => item.product.toString() === productId && 
                   item.size === size && 
                   item.color === color
        );

        if (cartItemIndex > -1) {
            // Update quantity if item exists
            user.cart[cartItemIndex].quantity += quantity;
        } else {
            // Add new item to cart
            user.cart.push({
                product: productId,
                quantity,
                size,
                color
            });
        }

        await user.save();

        // Return updated cart
        const updatedUser = await User.findById(req.user._id)
            .populate('cart.product')
            .select('cart');

        res.json(updatedUser.cart);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Update cart item quantity
router.put('/update/:productId', [
    auth,
    body('quantity').isInt({ min: 1 }).withMessage('Quantity must be at least 1'),
    body('size').isIn(['XS', 'S', 'M', 'L', 'XL', 'XXL']).withMessage('Invalid size'),
    body('color').notEmpty().withMessage('Color is required')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { productId } = req.params;
        const { quantity, size, color } = req.body;

        // Check if product exists and has enough stock
        const product = await Product.findById(productId);
        if (!product) {
            return res.status(404).json({ message: 'Product not found' });
        }

        if (product.stock < quantity) {
            return res.status(400).json({ message: 'Not enough stock available' });
        }

        // Update cart item
        const user = await User.findById(req.user._id);
        const cartItemIndex = user.cart.findIndex(
            item => item.product.toString() === productId && 
                   item.size === size && 
                   item.color === color
        );

        if (cartItemIndex === -1) {
            return res.status(404).json({ message: 'Item not found in cart' });
        }

        user.cart[cartItemIndex].quantity = quantity;
        await user.save();

        // Return updated cart
        const updatedUser = await User.findById(req.user._id)
            .populate('cart.product')
            .select('cart');

        res.json(updatedUser.cart);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Remove item from cart
router.delete('/remove/:productId', [
    auth,
    body('size').isIn(['XS', 'S', 'M', 'L', 'XL', 'XXL']).withMessage('Invalid size'),
    body('color').notEmpty().withMessage('Color is required')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { productId } = req.params;
        const { size, color } = req.body;

        const user = await User.findById(req.user._id);
        user.cart = user.cart.filter(
            item => !(item.product.toString() === productId && 
                     item.size === size && 
                     item.color === color)
        );

        await user.save();

        // Return updated cart
        const updatedUser = await User.findById(req.user._id)
            .populate('cart.product')
            .select('cart');

        res.json(updatedUser.cart);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Clear cart
router.delete('/clear', auth, async (req, res) => {
    try {
        const user = await User.findById(req.user._id);
        user.cart = [];
        await user.save();
        res.json({ message: 'Cart cleared successfully' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error' });
    }
});

module.exports = router; 