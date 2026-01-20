const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        trim: true
    },
    description: {
        type: String,
        required: true
    },
    price: {
        type: Number,
        required: true,
        min: 0
    },
    category: {
        type: String,
        required: true,
        enum: ['tshirts', 'hoodies', 'jeans', 'dresses', 'shorts']
    },
    images: [{
        type: String,
        required: true
    }],
    sizes: [{
        type: String,
        enum: ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        required: true
    }],
    colors: [{
        type: String,
        required: true
    }],
    stock: {
        type: Number,
        required: true,
        min: 0,
        default: 0
    },
    rating: {
        type: Number,
        default: 0,
        min: 0,
        max: 5
    },
    reviews: [{
        user: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'User'
        },
        rating: {
            type: Number,
            required: true,
            min: 1,
            max: 5
        },
        comment: {
            type: String,
            required: true
        },
        createdAt: {
            type: Date,
            default: Date.now
        }
    }],
    featured: {
        type: Boolean,
        default: false
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Index for better search performance
productSchema.index({ name: 'text', description: 'text' });

module.exports = mongoose.model('Product', productSchema); 