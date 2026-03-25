const fs = require('fs');
const path = require('path');

const API_VERSION = 'v1';
const BASE_URL = '{{baseUrl}}';
const ROUTES_DIR = path.join(__dirname, '../src/routes');
const CONTROLLERS_DIR = path.join(__dirname, '../src/controllers');
const OUTPUT_FILE = path.join(__dirname, '../postman_collection_full.json');

const FOLDER_MAPPING = {
    'auth': 'Auth & Identity Management', 'user': 'Auth & Identity Management', 'role': 'Auth & Identity Management',
    'apiAccess': 'Auth & Identity Management', 'auditLog': 'Auth & Identity Management',
    'specialist': 'Specialists & Scheduling', 'specialistSchedule': 'Specialists & Scheduling',
    'consult': 'Consultations (Teleconsult)', 'resource': 'Consultations (Teleconsult)',
    'clinicalSummary': 'Clinical Records & Summaries', 'chiefComplaint': 'Clinical Records & Summaries',
    'hpi': 'Clinical Records & Summaries', 'ros': 'Clinical Records & Summaries',
    'mse': 'Clinical Records & Summaries', 'pastHistory': 'Clinical Records & Summaries',
    'historyOfIllness': 'Clinical Records & Summaries', 'treatment': 'Clinical Records & Summaries',
    'symptom': 'Clinical Records & Summaries', 'assessment': 'Assessments & Questionnaires',
    'selfAssessment': 'Assessments & Questionnaires', 'professionalAssessment': 'Assessments & Questionnaires',
    'advancedAssessment': 'Assessments & Questionnaires', 'question': 'Assessments & Questionnaires',
    'dashboard': 'Analytical & AI Services', 'analytics': 'Analytical & AI Services', 'ai': 'Analytical & AI Services',
    'taxCode': 'Financial & Administrative', 'chargeCode': 'Financial & Administrative',
    'systemSetting': 'Financial & Administrative', 'scheduledJob': 'Financial & Administrative',
    'notification': 'Communication & Portals', 'familyPortal': 'Communication & Portals',
    'portal': 'Communication & Portals', 'feedback': 'Communication & Portals', 'step': 'Communication & Portals'
};

const BASE_PATH_MAPPING = {
    'health': '/health', 'auth': '/auth', 'user': '/users', 'notification': '/notifications',
    'question': '/questions', 'assessment': '/assessments', 'selfAssessment': '/self-assessments',
    'professionalAssessment': '/professional-assessments', 'resource': '/resource', 'consult': '/consults',
    'role': '/roles', 'apiAccess': '/api-access', 'taxCode': '/tax-codes', 'chargeCode': '/charge-codes',
    'specialist': '/specialists', 'specialistSchedule': '/specialists/schedule', 'dashboard': '/dashboard',
    'treatment': '/treatment', 'chiefComplaint': '/chief-complaints', 'historyOfIllness': '/history-of-illness',
    'hpi': '/hpis', 'ros': '/ros', 'pastHistory': '/past-history', 'mse': '/mse',
    'clinicalSummary': '/patients', 'advancedAssessment': '/clinical-assessments', 'analytics': '/analytics',
    'familyPortal': '/family-portal', 'ai': '/ai', 'auditLog': '/audit-logs', 'portal': '/portal',
    'feedback': '/feedback', 'systemSetting': '/system-settings', 'scheduledJob': '/scheduled-jobs',
    'symptom': '/symptoms'
};

const REAL_DATA_MAPPING = {
    // General / Auth
    'firstName': 'John', 'lastName': 'Doe', 'username': 'johndoe', 'email': 'johndoe@example.com',
    'password': 'password123', 'currentPassword': 'password123', 'newPassword': 'newpassword456',
    'confirmPassword': 'newpassword456', 'phone': '1234567890', 'role': 'patient', 'gender': 'male',
    'dateOfBirth': '1990-01-01', 'isActive': true, 'profileImage': 'https://example.com/profile.jpg',
    'address': '123 Main St, Springfield', 'city': 'Springfield', 'countryIso': 'US', 'isdCode': '+1',
    'mobile': '1234567890', 'bloodGroup': 'O+', 'timezoneId': 1, 'is2fa': false,
    
    // Identifiers
    'id': '65f1a2b3c4d5e6f7a8b9c0d1', 'userId': '65f1a2b3c4d5e6f7a8b9c0d1',
    'patientId': '65f1a2b3c4d5e6f7a8b9c0d1', 'specialistId': '65f1a2b3c4d5e6f7a8b9c0d2',
    'consultId': '65f1a2b3c4d5e6f7a8b9c0d3', 'roleId': '65f1a2b3c4d5e6f7a8b9c0d4',
    'assessmentId': '65f1a2b3c4d5e6f7a8b9c0d5', 'questionId': '65f1a2b3c4d5e6f7a8b9c0d6',
    'masterId': '65f1a2b3c4d5e6f7a8b9c0d7', 'hospitalId': '65f1a2b3c4d5e6f7a8b9c0d8',
    'professionalId': '65f1a2b3c4d5e6f7a8b9c0d9', 'token': 'jwt_test_1234567890abcdef',
    'refreshToken': 'rt_test_1234567890abcdef', 'apiKey': 'ak_test_1234567890abcdef',
    
    // Core Attributes
    'code': 'CODE-001', 'name': 'Sample Clinical Module', 'title': 'Patient Assessment Report',
    'description': 'Comprehensive mental health assessment for the primary patient.',
    'content': 'The patient exhibits signs of moderate anxiety and sleep disturbance.',
    'category': 'clinical', 'priority': 'medium', 'status': 'active', 'type': 'standard',
    'tags': ['mental-health', 'demo', 'clinical'], 'notes': 'Follow up in two weeks.',
    
    // Dates & Times
    'date': '2026-03-19', 'startTime': '09:00', 'endTime': '10:00', 'duration': 60,
    'time': '09:30', 'startDate': '2026-03-19', 'endDate': '2026-04-19',
    
    // Professional Profile
    'specialization': 'Clinical Psychology', 'experienceYears': 12,
    'about': 'Expert in mental health and clinical psychology with 12 years of experience.',
    'qualifications': ['PhD in Psychology', 'MSc in Clinical Mental Health'],
    'languages': ['English', 'Spanish', 'French'], 'consultationFee': 150,
    'skills': ['CBT', 'DBT', 'Family Therapy', 'Trauma-Informed Care'], 'isVerified': true,
    
    // Clinical Data
    'severity': 'Moderate', 'inference': 'Likely generalized anxiety disorder symptoms.',
    'summary': 'The patient is making steady progress but requires continued therapy.',
    'chiefComplaint': 'Persistent work-related stress and occasional panic attacks.',
    'history': 'No significant previous psychiatric hospitalizations.',
    'totalScore': 45, 'level': 'intermediate', 'reason': 'Regular monthly checkup',
    
    // Financial & Administrative
    'taxPercentage': 12.5, 'chargeCode': 'CONSULT-01', 'amount': 250,
    'currency': 'USD', 'jobName': 'Nightly Backup & Audit', 'schedule': '0 0 * * *',
    
    // Complex Objects
    'fcmTokens': ['fcm_token_sample_123'],
    'communicationPreferences': { 'email': true, 'sms': true, 'push': true },
    'emergencyContact': 'Jane Smith (+1 555-010-9988)',
    'coordinates': { 'lat': 34.0522, 'lng': -118.2437 }
};

const collection = {
    info: {
        name: 'Mental Health API (Full Real Data v2)',
        _postman_id: 'a-' + Date.now(),
        description: 'Comprehensive Postman collection for the Mental Health API, automatically generated with real-world payloads and documentation.',
        schema: 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
    },
    item: [],
    variable: [
        { key: 'baseUrl', value: 'http://localhost:5000/api/v1', type: 'string' },
        { key: 'apiKey', value: '{{apiKey}}', type: 'string' },
        { key: 'token', value: '{{token}}', type: 'string' }
    ]
};

const folderItems = {};

function getOrCreateTopFolder(name) {
    if (!folderItems[name]) {
        folderItems[name] = { name, item: [], description: `Endpoints related to ${name}` };
        collection.item.push(folderItems[name]);
    }
    return folderItems[name];
}

function parseController(content) {
    const handlers = {};
    const handlerIndices = [];
    const handlerRegex = /(?:exports\.(\w+)|const\s+(\w+))\s*=\s*(?:async\s*)?\(/g;
    
    let m;
    while ((m = handlerRegex.exec(content)) !== null) {
        handlerIndices.push({ index: m.index, name: m[1] || m[2] });
    }
    
    handlerIndices.forEach((hi, i) => {
        const nextIndex = handlerIndices[i+1] ? handlerIndices[i+1].index : content.length;
        const prevIndex = i === 0 ? 0 : handlerIndices[i-1].index;
        
        const beforeText = content.slice(prevIndex, hi.index);
        const bodyText = content.slice(hi.index, nextIndex);
        
        const descMatch = beforeText.match(/@desc\s+([^\r\n]+)/);
        const routeMatch = beforeText.match(/@route\s+(\w+)\s+([^\r\n]+)/);
        const accessMatch = beforeText.match(/@access\s+([^\r\n]+)/);
        
        const bodyPatterns = [
            /const\s+\{([^}]+)\}\s*=\s*req\.body/g,
            /let\s+\{([^}]+)\}\s*=\s*req\.body/g,
            /var\s+\{([^}]+)\}\s*=\s*req\.body/g
        ];
        
        const fields = new Set();
        bodyPatterns.forEach(pattern => {
            let bm;
            while ((bm = pattern.exec(bodyText)) !== null) {
                bm[1].split(',').forEach(f => {
                    const clean = f.trim().split(':')[0].split('=')[0].trim();
                    if (clean && !['req', 'res', 'next'].includes(clean)) fields.add(clean);
                });
            }
        });

        handlers[hi.name] = {
            desc: descMatch ? descMatch[1].trim().replace(/\s*\*\/$/, '') : hi.name,
            method: routeMatch ? routeMatch[1].toUpperCase() : 'GET',
            access: accessMatch ? accessMatch[1].trim().replace(/\s*\*\/$/, '') : 'Private',
            body: Array.from(fields).reduce((acc, f) => { 
                acc[f] = REAL_DATA_MAPPING[f] !== undefined ? REAL_DATA_MAPPING[f] : '...'; 
                return acc; 
            }, {})
        };
    });
    
    return handlers;
}

function generateCollection() {
    const routeFiles = fs.readdirSync(ROUTES_DIR).filter(f => f.endsWith('.routes.js'));
    let totalRequests = 0;

    routeFiles.forEach(file => {
        const moduleName = file.replace('.routes.js', '');
        const routeContent = fs.readFileSync(path.join(ROUTES_DIR, file), 'utf8');
        
        const controllerMatch = routeContent.match(/require\('\.\.\/controllers\/([^']+)'\)/);
        if (!controllerMatch) return;
        
        const controllerPath = path.join(CONTROLLERS_DIR, controllerMatch[1] + '.js');
        if (!fs.existsSync(controllerPath)) return;
        
        const controllerContent = fs.readFileSync(controllerPath, 'utf8');
        const handlers = parseController(controllerContent);
        
        const topFolder = getOrCreateTopFolder(FOLDER_MAPPING[moduleName] || 'Other');
        const moduleFolder = { name: moduleName, item: [], description: `Endpoints for ${moduleName}` };
        topFolder.item.push(moduleFolder);
        
        const basePath = BASE_PATH_MAPPING[moduleName] || `/${moduleName}`;
        
        const routerStartRegex = /router\.([a-z]+)\s*\(/g;
        while ((m = routerStartRegex.exec(routeContent)) !== null) {
            const method = m[1];
            const start = m.index + m[0].length;
            const args = extractArguments(routeContent, start);
            
            if (method === 'route') {
                const relPath = args[0].replace(/['"]/g, '');
                const chainIdx = start + getActualArgLength(routeContent, start);
                const chainText = routeContent.slice(chainIdx).match(/^(\s*\.[a-z]+\(\s*[\s\S]+?\))+/);
                if (chainText) {
                    const subCalls = chainText[0].matchAll(/\.([a-z]+)\s*\(([\s\S]+?)\)/g);
                    for (const sc of subCalls) {
                        const smethod = sc[1];
                        const sargs = extractArguments(sc[0], sc[0].indexOf('(') + 1);
                        const handlerName = sargs[sargs.length - 1];
                        if (addRequest(moduleFolder, basePath, relPath, smethod, handlerName, handlers)) totalRequests++;
                    }
                }
            } else {
                const relPath = args[0].replace(/['"]/g, '');
                const handlerName = args[args.length - 1];
                if (addRequest(moduleFolder, basePath, relPath, method, handlerName, handlers)) totalRequests++;
            }
        }
    });

    fs.writeFileSync(OUTPUT_FILE, JSON.stringify(collection, null, 2));
    console.log(`Success! Generated Postman collection v7 (Full Real Data).`);
    console.log(`Total Categories: ${collection.item.length}`);
    console.log(`Total Requests Generated: ${totalRequests}`);
}

function extractArguments(text, start) {
    let depth = 1;
    let i = start;
    let current = '';
    let args = [];
    while (depth > 0 && i < text.length) {
        let char = text[i];
        if (char === '(') depth++;
        if (char === ')') depth--;
        
        if (depth === 1 && char === ',') {
            args.push(current.trim());
            current = '';
        } else if (depth > 0) {
            current += char;
        }
        i++;
    }
    args.push(current.trim());
    return args;
}

function getActualArgLength(text, start) {
    let depth = 1;
    let i = start;
    while (depth > 0 && i < text.length) {
        if (text[i] === '(') depth++;
        if (text[i] === ')') depth--;
        i++;
    }
    return i - start;
}

function addRequest(folder, basePath, relPath, method, handlerName, handlers) {
    if (method === 'use') return false;
    const handler = handlers[handlerName] || { desc: handlerName, method: method.toUpperCase(), access: 'Private', body: {} };
    
    const fullPath = (basePath + relPath).replace(/\/+/g, '/').replace(/\/$/, '');
    const postmanPath = fullPath.split('/').filter(p => p).map(p => p.startsWith(':') ? `:${p.slice(1)}` : p);
    
    const request = {
        name: handler.desc || `${method.toUpperCase()} ${fullPath}`,
        request: {
            method: method.toUpperCase(),
            header: [
                { key: 'api-key', value: '{{apiKey}}' },
                { key: 'Authorization', value: 'Bearer {{token}}' },
                { key: 'Content-Type', value: 'application/json' }
            ],
            url: {
                raw: `{{baseUrl}}${fullPath}`,
                host: ['{{baseUrl}}'],
                path: postmanPath
            },
            description: `**Description:** ${handler.desc}\n**Access:** ${handler.access}\n**Handler:** ${handlerName}`
        }
    };
    
    if (['POST', 'PUT', 'PATCH'].includes(method.toUpperCase())) {
        request.request.body = {
            mode: 'raw',
            raw: JSON.stringify(handler.body, null, 4),
            options: { raw: { language: 'json' } }
        };
    }
    
    folder.item.push(request);
    return true;
}

generateCollection();
