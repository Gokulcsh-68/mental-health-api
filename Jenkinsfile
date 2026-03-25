pipeline {
agent any

environment {
    IMAGE_NAME = "localhost:5000/nodejs-backend"
    CONTAINER  = "nodejs-backend"
    PORT       = "8201"
    NETWORK    = "uat-network"
}

stages {
    stage('Checkout') {
        steps { checkout scm }
    }

    stage('Install & Test') {
        steps {
            sh 'npm ci'
            sh 'npm test --if-present'
        }
    }

    stage('Build image') {
        steps {
            sh 'docker build -t ${IMAGE_NAME}:${BUILD_NUMBER} -t ${IMAGE_NAME}:latest .'
        }
    }

    stage('Push to registry') {
        steps {
            sh 'docker push ${IMAGE_NAME}:${BUILD_NUMBER}'
            sh 'docker push ${IMAGE_NAME}:latest'
        }
    }

    stage('Deploy') {
        steps {
            sh '''
                docker stop ${CONTAINER} || true
                docker rm   ${CONTAINER} || true
                docker pull ${IMAGE_NAME}:latest
                docker run -d \
                    --name ${CONTAINER} \
                    --network ${NETWORK} \
                    --restart unless-stopped \
                    -p ${PORT}:3000 \
                    --env-file /srv/uat/nodejs/.env \
                    ${IMAGE_NAME}:latest
            '''
        }
    }
}

post {
    success { echo "Node.js deployed at http://SERVER_IP:${PORT}" }
    failure {
        mail to: 'your-email@example.com',
             subject: "FAILED: Node.js pipeline #${BUILD_NUMBER}",
             body: "Check Jenkins: ${BUILD_URL}"
    }
    always { sh 'docker image prune -f' }
}