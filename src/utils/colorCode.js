/**
 * @desc  Centralized clinical color code utility
 *        Used by HPI, ChiefComplaint, and any future clinical modules
 *
 * Hex Scale:
 *   #4CAF50 (green)  = severity 0-30   — No/Low risk, monitor only
 *   #FDD835 (yellow) = severity 31-50  — Mild impairment
 *   #FB8C00 (orange) = severity 51-75  — Moderate impairment or risk
 *   #E53935 (red)    = severity 76-100 — Severe / Critical / Active risk
 */
const autoColorCode = (severityIndex = 0) => {
    if (severityIndex >= 76) return '#E53935'; // red
    if (severityIndex >= 51) return '#FB8C00'; // orange
    if (severityIndex >= 31) return '#FDD835'; // yellow
    return '#4CAF50';                           // green
};

module.exports = { autoColorCode };
