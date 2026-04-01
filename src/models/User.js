const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const crypto = require('crypto');
const config = require('../config/config');
const Counter = require('./Counter');

const UserSchema = new mongoose.Schema({
    userId: {
        type: Number,
        unique: true
    },
    firstName: {
        type: String,
        required: [true, 'Please add a first name']
    },
    lastName: {
        type: String,
        required: [true, 'Please add a last name']
    },
    username: {
        type: String,
        required: [true, 'Please add a username'],
        unique: true
    },
    phone: {
        type: String,
        required: [true, 'Please add a phone number']
    },
    dateOfBirth: {
        type: Date
    },
    gender: {
        type: String,
        enum: ['male', 'female', 'other']
    },
    isdCode: {
        type: String
    },
    mobile: {
        type: String
    },
    profileImage: {
        type: String
    },
    bloodGroup: {
        type: String
    },
    timezoneId: {
        type: Number
    },
    countryIso: {
        type: String
    },
    is2fa: {
        type: Boolean,
        default: false
    },
    secret: {
        type: String,
        select: false
    },
    isActive: {
        type: Boolean,
        default: true
    },
    communicationPreferences: {
        email: { type: Boolean, default: true },
        sms: { type: Boolean, default: true },
        push: { type: Boolean, default: true }
    },
    fcmTokens: {
        type: [String]
    },
    createdBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    updatedBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    address: {
        type: String
    },
    city: {
        type: String
    },
    coordinates: {
        lat: Number,
        lng: Number
    },
    emergencyContact: {
        type: String
    },
    email: {
        type: String,
        required: [true, 'Please add an email'],
        unique: true,
        match: [
            /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
            'Please add a valid email'
        ]
    },
    password: {
        type: String,
        required: [true, 'Please add a password'],
        validate: {
            validator: function(v) {
                // Minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(v);
            },
            message: 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.'
        },
        select: false
    },
    role: {
        type: String,
        enum: ['super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'patient', 'family'],
        default: 'patient'
    },
    loginAttempts: {
        type: Number,
        required: true,
        default: 0
    },
    lockUntil: {
        type: Date
    },
    family_consents: [{
        familyUserId: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
        modules: [String], // e.g., ["prescriptions", "follow_up", "clinical_record"]
        is_active: { type: Boolean, default: true },
        updated_at: { type: Date, default: Date.now }
    }],
    reportingTo: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    hospital: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    professional: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    consultChargeCode: {
        type: Number
    },
    resetPasswordToken: {
        type: String,
        select: false
    },
    resetPasswordExpire: {
        type: Date,
        select: false
    },
    // Professional Profile Fields
    specialization: {
        type: String,
        trim: true
    },
    about: {
        type: String,
        trim: true
    },
    experienceYears: {
        type: Number,
        min: 0
    },
    qualifications: {
        type: [String]
    },
    languages: {
        type: [String]
    },
    consultationFee: {
        type: Number,
        min: 0
    },
    skills: {
        type: [String]
    },
    isVerified: {
        type: Boolean,
        default: false
    },
    verifiedAt: {
        type: Date
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Auto-increment ID
UserSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'userId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.userId = counter.seq;
});

// Encrypt password using bcrypt
UserSchema.pre('save', async function () {
    if (!this.isModified('password')) {
        return;
    }

    const salt = await bcrypt.genSalt(10);
    this.password = await bcrypt.hash(this.password, salt);
});

// Sign JWT and return
UserSchema.methods.getSignedJwtToken = function () {
    return jwt.sign({ _id: this._id, userId: this.userId }, config.JWT_SECRET, {
        expiresIn: config.JWT_EXPIRE
    });
};

// Match user entered password to hashed password in database
UserSchema.methods.matchPassword = async function (enteredPassword) {
    return await bcrypt.compare(enteredPassword, this.password);
};

// Generate and hash password reset OTP (6-digit)
UserSchema.methods.getResetPasswordToken = function () {
    // Generate 6-digit numeric OTP
    const resetToken = Math.floor(100000 + Math.random() * 900000).toString();

    this.resetPasswordToken = crypto.createHash('sha256').update(resetToken).digest('hex');
    this.resetPasswordExpire = Date.now() + 10 * 60 * 1000; // 10 minutes

    return resetToken;
};

module.exports = mongoose.model('User', UserSchema);
