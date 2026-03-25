const Consult = require('../models/Consult');
const User = require('../models/User');
const logger = require('../config/logger');

/**
 * @desc    Get Central AI Analytics Dashboard Data
 * @route   GET /api/v1/analytics/dashboard
 * @access  Private/Admin
 */
exports.getDashboardStats = async (req, res) => {
    try {
        const stats = await Consult.aggregate([
            {
                $group: {
                    _id: "$city",
                    total_consults: { $sum: 1 },
                    avg_risk_score: { $avg: "$clinical_record.ai_inference.risk_stratification.score" },
                    symptom_summary: { $push: "$clinical_record.chief_complaints.structured.triggers" }
                }
            },
            { $sort: { total_consults: -1 } }
        ]);

        const populationRisk = await Consult.aggregate([
            {
                $group: {
                    _id: "$clinical_record.ai_inference.risk_stratification.level",
                    count: { $sum: 1 }
                }
            }
        ]);

        res.status(200).json({
            code: 200,
            message: 'Central analytics retrieved successfully',
            data: {
                by_city: stats,
                risk_distribution: populationRisk,
                timestamp: new Date().toISOString()
            }
        });
    } catch (err) {
        logger.error('Analytics Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * @desc    Get Geographic Symptom Heat Map
 * @route   GET /api/v1/analytics/heat-map
 * @access  Private/Admin
 */
exports.getSymptomHeatMap = async (req, res) => {
    try {
        const heatMap = await Consult.aggregate([
            {
                $match: {
                    "coordinates.lat": { $exists: true },
                    "clinical_record.ai_inference.differential_diagnosis": { $exists: true }
                }
            },
            {
                $project: {
                    city: 1,
                    coordinates: 1,
                    primary_diagnosis: "$clinical_record.diagnosis.primary.condition",
                    risk_level: "$clinical_record.ai_inference.risk_stratification.level"
                }
            }
        ]);

        res.status(200).json({
            code: 200,
            message: 'Geographic symptom clusters retrieved successfully',
            data: heatMap
        });
    } catch (err) {
        logger.error('Heat Map Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
